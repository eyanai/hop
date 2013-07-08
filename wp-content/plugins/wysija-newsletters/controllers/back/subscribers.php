<?php
defined('WYSIJA') or die('Restricted access');
class WYSIJA_control_back_subscribers extends WYSIJA_control_back{
    var $model="user";
    var $view="subscribers";
    var $list_columns=array("user_id","firstname", "lastname","email","created_at");
    var $searchable=array('email','firstname', 'lastname');
    var $_export_batch = 2000; //set batch of records, useful when retrieving the list of user Ids to export
    var $_separators = array(',', ';'); // csv separator; comma is for standard csv, semi-colon is good for Excel
    var $_default_separator = ';';

    function WYSIJA_control_back_subscribers(){

    }

    function save(){
        $this->redirectAfterSave=false;
        $helperUser=WYSIJA::get('user','helper');
        if(isset($_REQUEST['id'])){
            $id=$_REQUEST['id'];
            parent::save();

            //run the unsubscribe process if needed
            if((int)$_REQUEST['wysija']['user']['status']==-1){
                $helperUser->unsubscribe($id);
            }

            /* update subscriptions */
            $modelUL=WYSIJA::get('user_list','model');
            $modelUL->backSave=true;
            /* list of core list */
            $modelLIST=WYSIJA::get('list','model');
            $results=$modelLIST->get(array('list_id'),array('is_enabled'=>'0'));
            $core_listids=array();
            foreach($results as $res){
                $core_listids[]=$res['list_id'];
            }

            //0 - get current lists of the user
            $userlists=$modelUL->get(array('list_id','unsub_date'),array('user_id'=>$id));

            $oldlistids=$newlistids=array();
            foreach($userlists as $listdata)    $oldlistids[$listdata['list_id']]=$listdata['unsub_date'];

            $config=WYSIJA::get('config','model');
            $dbloptin=$config->getValue('confirm_dbleoptin');
            //1 - insert new user_list
            if(isset($_POST['wysija']['user_list']) && $_POST['wysija']['user_list']){
                $modelUL->reset();
                $modelUL->update(array('sub_date'=>time()),array('user_id'=>$id));
                if(!empty($_POST['wysija']['user_list']['list_id'])){
                    foreach($_POST['wysija']['user_list']['list_id'] as $list_id){
                        //if the list is not already recorded for the user then we will need to insert it
                        if(!isset($oldlistids[$list_id])){
                            $modelUL->reset();
                            $newlistids[]=$list_id;
                            $dataul=array('user_id'=>$id,'list_id'=>$list_id,'sub_date'=>time());
                            //if double optin is on and user is unconfirmed or unsubscribed, then we need to set it as unconfirmed subscription
                            if($dbloptin && (int)$_POST['wysija']['user']['status']<1)  unset($dataul['sub_date']);
                            $modelUL->insert($dataul);
                        //if the list is recorded already then let's check the status, if it is an unsubed one then we update it
                        }else{
                            if($oldlistids[$list_id]>0){
                                $modelUL->reset();
                                $modelUL->update(array('unsub_date'=>0,'sub_date'=>time()),array('user_id'=>$id,'list_id'=>$list_id));
                            }
                        }
                    }
                }

            }

            //if a confirmation email needs to be sent then we send it
            if($dbloptin && (int)$_POST['wysija']['user']['status']==0 && !empty($newlistids)){
                $hUser=WYSIJA::get('user','helper');
                $hUser->sendConfirmationEmail($id,true,$newlistids);
            }

            if((int)$_POST['wysija']['user']['status']==0 || (int)$_POST['wysija']['user']['status']==1){
                $modelUL->reset();
                $modelUL->update(array('unsub_date'=>0,'sub_date'=>time()),array('user_id'=>$id,'list_id'=>$core_listids));
            }

            $arrayLists=array();
            if(isset($_POST['wysija']['user_list']['list_id'])) $arrayLists=$_POST['wysija']['user_list']['list_id'];
            $notEqual=array_merge($core_listids, $arrayLists);

            //unsubscribe from lists which exist in the old list but does not exist in the new list
            $unsubsribe_list = array_diff(array_keys($oldlistids),$_POST['wysija']['user_list']['list_id']);
            if(!empty($unsubsribe_list))
            {
                $modelUL->reset();
                $modelUL->update(array('unsub_date'=>time()),array('user_id'=>$id,'list_id'=>$unsubsribe_list));
            }
            $modelUL->reset();
        }else{
            //instead of going through a classic save we should save through the helper
            $data=$_REQUEST['wysija'];
            $data['user_list']['list_ids'] = !empty($data['user_list']['list_id']) ? $data['user_list']['list_id'] : array();
            unset($data['user_list']['list_id']);
            $data['message_success']=__('Subscriber has been saved.',WYSIJA);
            $id=$helperUser->addSubscriber($data,true);
            //$id= parent::save();
            if(!$id) {
                $this->viewShow=$this->action='add';
                $data=array('details'=>$_REQUEST['wysija']['user']);
                return $this->add($data);
            }
        }
        $this->redirect();
        return true;
    }

    function defaultDisplay(){
        $this->viewShow=$this->action='main';
        $this->js[]='wysija-admin-list';
        $this->viewObj->msgPerPage=__('Subscribers per page:',WYSIJA);

        $this->jsTrans['selecmiss']=__('Select at least 1 subscriber!',WYSIJA);
        $orphaned=$filterJoin=false;
        //get the filters
        if(isset($_REQUEST['search']) && $_REQUEST['search']){
            $this->filters['like']=array();
            $_REQUEST['search']=trim($_REQUEST['search']);
            foreach($this->searchable as $field)
                $this->filters['like'][$field]=trim($_REQUEST['search']);
        }

        // Lists filters
        // - override wysija[filter][filter-list] if this is empty
        if(
                !empty($_REQUEST['redirect'])
                && (!empty($_REQUEST['filter_list']) || !empty($_REQUEST['filter-list']))
                && empty($_REQUEST['wysija']['filter']['filter_list'])
                )
            $_REQUEST['wysija']['filter']['filter_list'] = !empty ($_REQUEST['filter_list']) ? $_REQUEST['filter_list'] : $_REQUEST['filter-list'];

        if(isset($_REQUEST['wysija']['filter']['filter_list']) && $_REQUEST['wysija']['filter']['filter_list']){
            if ($_REQUEST['wysija']['filter']['filter_list'] == 'orphaned') {
                $this->filters['equal']['list_id'] = null;
                $orphaned = true;
            } else {
                //we only get subscribed or unconfirmed users
                $this->filters['equal']['list_id'] = $_REQUEST['wysija']['filter']['filter_list'];
                $filterJoin=true;
            }
        }

        $config=WYSIJA::get('config','model');
        if(isset($_REQUEST['link_filter']) && $_REQUEST['link_filter']){
            switch($_REQUEST['link_filter']){
                case 'unconfirmed':
                    $this->filters['equal']['status'] = 0;
                    break;
                case 'unsubscribed':
                    $this->filters['equal']['status'] = -1;
                    break;
                case 'subscribed':
                    if($config->getValue('confirm_dbleoptin'))  $this->filters['equal']['status'] = 1;
                    else $this->filters['greater_eq']=array('status'=>0);
                    break;
            }
        }

        $this->modelObj->noCheck=true;


        //0 - counting request */
        if($filterJoin){
            $queryCmmonStart='SELECT count(distinct A.user_id) as users, max(A.created_at) as max_create_at FROM `[wysija]user_list` as B';
            $queryCmmonStart.=' JOIN `[wysija]'.$this->modelObj->table_name.'` as A on A.user_id=B.user_id';
        } elseif($orphaned) {
            $queryCmmonStart='SELECT count(distinct A.user_id) as users, max(A.created_at) as max_create_at FROM `[wysija]user` as A';
            $queryCmmonStart.=' LEFT JOIN `[wysija]user_list` as B on A.user_id=B.user_id';
        } else {
            $queryCmmonStart='SELECT count(distinct A.user_id) as users, max(A.created_at) as max_create_at FROM `[wysija]'.$this->modelObj->table_name.'` as A';
        }


        //all the counts query */
        $query='SELECT count(user_id) as users, status, max(created_at) as max_create_at FROM `[wysija]'.$this->modelObj->table_name.'` GROUP BY status';
        $countss=$this->modelObj->query('get_res',$query,ARRAY_A);
        $counts=array();
        $total=0;

        $arr_max_create_at = array();
        foreach($countss as $count){
            switch($count['status']){
                case '0':
                    $type='unconfirmed';
                    break;
                case '-1':
                    $type='unsubscribed';
                    break;
                case '1':
                    $type='subscribed';
                    break;
            }
            $arr_max_create_at[] = $count['max_create_at'];
            $total=$total+$count['users'];
            $counts[$type]=$count['users'];
        }
        //if(!isset($counts["unconfirmed"])) $counts["unconfirmed"]=0;
        if(!$config->getValue('confirm_dbleoptin'))  {
            if(isset($counts['subscribed'])) {
                if(isset($counts['unconfirmed']))   $counts['subscribed']=$counts['subscribed']+$counts['unconfirmed'];
                else $counts['subscribed']=$counts['subscribed'];

            }else{
                $counts['subscribed']=$counts['unconfirmed'];
            }
            unset($counts['unconfirmed']);
        }

        $counts['all']=$total;

        $this->modelObj->reset();

        if($this->filters)  $this->modelObj->setConditions($this->filters);


        //1 - user request

        if($filterJoin){

            $query="SELECT distinct(A.user_id), A.firstname, A.lastname,A.status , A.email, A.created_at FROM `[wysija]user_list` as B";
            $query.=" JOIN `[wysija]".$this->modelObj->table_name."` as A on A.user_id=B.user_id";

        }elseif($orphaned) {

            $query="SELECT distinct(A.user_id), A.firstname, A.lastname,A.status , A.email, A.created_at FROM `[wysija]user` as A";
            $query.=" LEFT JOIN `[wysija]user_list` as B on B.user_id=A.user_id";

        } else {
            $query="SELECT distinct(A.user_id), A.firstname, A.lastname,A.status , A.email, A.created_at FROM `[wysija]".$this->modelObj->table_name."` as A";
        }

        $queryFinal=$this->modelObj->makeWhere();

        /* without filter we already have the total number of subscribers */
        $max_create_at = null; //max value of create_at field of current list of users

        if($this->filters){
            $count_rows = $this->modelObj->getResults($queryCmmonStart.$queryFinal);
            $count_rows = $count_rows[0];
            $this->modelObj->countRows = $count_rows['users'];
            $max_create_at = $count_rows['max_create_at'];
        }
        else{
            $max_create_at = !empty($arr_max_create_at) ? max($arr_max_create_at) : 0;
            $this->modelObj->countRows=$counts['all'];
        }

        $orderby=' ORDER BY ';
        if(isset($_REQUEST['orderby'])){
            $orderby.=$_REQUEST['orderby'].' '.$_REQUEST['ordert'];
        }else{
            $orderby.=$this->modelObj->pk.' desc';
        }
        $this->data['max_create_at'] = $max_create_at;
        $this->data['subscribers']=$this->modelObj->getResults($query.$queryFinal." ".$orderby.$this->modelObj->setLimit());
        $this->data['current_counts'] = $this->modelObj->countRows;
        $this->data['show_batch_select'] = ($this->modelObj->limit >= $this->modelObj->countRows) ? false : true;
        $this->modelObj->reset();

        /*make the data object for the listing view*/
        $modelList=WYSIJA::get('list','model');
        $modelList->reset();
        $listsAll=$modelList->getRows(false);
        /* 2 - list request */

        /*$query="SELECT A.list_id, A.name, count( B.user_id ) AS users FROM `".$modelList->getPrefix().$modelList->table_name."` as A";
        $query.=" JOIN `".$modelList->getPrefix()."user_list` as B on A.list_id = B.list_id";
        $query.=" GROUP BY A.list_id";


        $listsDB=$modelList->getResults($query); */
        $listsDB=$modelList->getLists();
        $lists=array();
        foreach($listsAll as $listobje){
            $lists[$listobje['list_id']]=$listobje;
        }
        foreach($listsDB as $listobj){
            if($listobj['subscribers']) $lists[$listobj['list_id']]['users']=$listobj['subscribers'];
        }
        $listsDB=null;

        $user_ids=array();
        foreach($this->data['subscribers'] as $subscriber){
            $user_ids[]=$subscriber['user_id'];
        }

        /* 3 - user_list request */
        if($user_ids){
            $modeluList=WYSIJA::get('user_list','model');
            $userlists=$modeluList->get(array('list_id','user_id','unsub_date'),array('user_id'=>$user_ids));
        }

        $this->data['lists']=$lists;
        $this->data['counts']=array_reverse($counts);



        /* regrouping all the data in the same array */
       foreach($this->data['subscribers'] as $keysus=>$subscriber){
            /* default key while we don't have the data*/
            //TODO add data for stats about emails opened clicked etc
            $this->data['subscribers'][$keysus]['emails']=0;
            $this->data['subscribers'][$keysus]['opened']=0;
            $this->data['subscribers'][$keysus]['clicked']=0;

            if($userlists){
                foreach($userlists as $key=>$userlist){
                    if($subscriber['user_id']==$userlist['user_id'] && isset($lists[$userlist['list_id']])){
                        //what kind of list ist it ? unsubscribed ? or not

                        if($userlist['unsub_date']>0){
                            if(!isset($this->data['subscribers'][$keysus]['unsub_lists']) ){
                                $this->data['subscribers'][$keysus]['unsub_lists']=$this->data['lists'][$userlist['list_id']]['name'];
                            }else{
                                $this->data['subscribers'][$keysus]['unsub_lists'].=', '.$this->data['lists'][$userlist['list_id']]['name'];
                            }
                       }else{
                            if(!isset($this->data['subscribers'][$keysus]['lists']) ){
                                $this->data['subscribers'][$keysus]['lists']=$this->data['lists'][$userlist['list_id']]['name'];
                            }else{
                                $this->data['subscribers'][$keysus]['lists'].=', '.$this->data['lists'][$userlist['list_id']]['name'];
                            }

                        }

                    }
                }
            }
        }
        if(!$this->data['subscribers']){
            $this->notice(__('Yikes! Couldn\'t find any subscribers.',WYSIJA));
        }

    }

    function main(){
         $this->messages['insert'][true]=__('Subscriber has been saved.',WYSIJA);
        $this->messages['insert'][false]=__('Subscriber has not been saved.',WYSIJA);
        $this->messages['update'][true]=__('Subscriber has been modified. [link]Edit again[/link].',WYSIJA);
        $this->messages['update'][false]=__('Subscriber has not been modified.',WYSIJA);
        parent::WYSIJA_control_back();

        //we change the default model of the controller based on the action
        if(isset($_REQUEST['action'])){
            switch($_REQUEST['action']){
                case 'listsedit':
                case 'savelist':
                case 'lists':
                    $this->model='list';
                    break;
                default:
                    $this->model='user';
            }
        }

        $this->WYSIJA_control();
        if(!isset($_REQUEST['action']) || !$_REQUEST['action']) {
            $this->defaultDisplay();
            $this->checkTotalSubscribers();
        }
        else $this->_tryAction($_REQUEST['action']);

    }

    /**
     * bulk action copy to list
     * @global type $wpdb
     * @param type $data
     */
    function copytolist($data){
        $helpU=WYSIJA::get('user','helper');
        if(empty($this->_batch_select))
            $helpU->addToList($data['listid'],$_POST['wysija']['user']['user_id']);
        else
            $helpU->addToList($data['listid'],$this->_batch_select, true);

        $modelL=WYSIJA::get('list','model');
        $result=$modelL->getOne(array('name'),array('list_id'=>$data['listid']));

        if($this->_affected_rows > 1)
            $this->notice(sprintf(__('%1$s subscribers have been added to "%2$s".',WYSIJA),$this->_affected_rows,$result['name']));
        else
            $this->notice(sprintf(__('%1$s subscriber have been added to "%2$s".',WYSIJA),$this->_affected_rows,$result['name']));
        $this->redirect('admin.php?page=wysija_subscribers&filter-list='.$data['listid']);
    }

    /**
     * bulk action move to list
     * @param type $data = array('list_id'=>?)
     */
    function movetolist($data){
        $helpU=WYSIJA::get('user','helper');
        if(!empty($this->_batch_select))
            $helpU->moveToList($data['listid'],$this->_batch_select, true);
        else
            $helpU->moveToList($data['listid'],$_POST['wysija']['user']['user_id']);

        $modelL=WYSIJA::get('list','model');
        $result=$modelL->getOne(array('name'),array('list_id'=>$data['listid']));

        if($this->_affected_rows > 1)
            $this->notice(sprintf(__('%1$s subscribers have been moved to "%2$s".',WYSIJA),$this->_affected_rows,$result['name']));
        else
            $this->notice(sprintf(__('%1$s subscriber have been moved to "%2$s".',WYSIJA),$this->_affected_rows,$result['name']));
        $this->redirect('admin.php?page=wysija_subscribers&filter-list='.$data['listid']);
    }

    /**
     * Bulk action remove subscribers from all existing lists
     * @param type $data = array('list_id'=>?)
     */
    function removefromalllists($data){
        $helpU=WYSIJA::get('user','helper');
        if(!empty($this->_batch_select))
            $helpU->removeFromLists(array(),$this->_batch_select, true);
        else
            $helpU->removeFromLists(array(),$_POST['wysija']['user']['user_id']);

        if($this->_affected_rows > 1)
            $this->notice(sprintf(__('%1$s subscribers have been removed from all exising lists.',WYSIJA),$this->_affected_rows));
        else
            $this->notice(sprintf(__('%1$s subscriber have been removed from all exising lists.',WYSIJA),$this->_affected_rows));
        $this->defaultDisplay();
    }

    /**
     * Bulk action remove subscribers from all existing lists
     * @param type $data = array('list_id'=>?)
     */
    function removefromlist($data = array()){
        $helpU=WYSIJA::get('user','helper');
        if(!empty($this->_batch_select))
            $helpU->removeFromLists(array($data['listid']),$this->_batch_select, true);
        else
            $helpU->removeFromLists(array($data['listid']),$_POST['wysija']['user']['user_id']);
        $modelL=WYSIJA::get('list','model');
        $result=$modelL->getOne(array('name'),array('list_id'=>$data['listid']));

        if($this->_affected_rows > 1)
            $this->notice(sprintf(__('%1$s subscribers have been removed from "%2$s".',WYSIJA),$this->_affected_rows, $result['name']));
        else
            $this->notice(sprintf(__('%1$s subscriber have been removed from "%2$s".',WYSIJA),$this->_affected_rows, $result['name']));
        $this->redirect('admin.php?page=wysija_subscribers&filter-list='.$data['listid']);
    }

    /**
     * Bulk confirm users
     */
    function confirmusers(){
        $helpU=WYSIJA::get('user','helper');
        if(!empty($this->_batch_select))
            $helpU->confirmUsers($this->_batch_select, true);
        else
            $helpU->confirmUsers($_POST['wysija']['user']['user_id']);

        if($this->_affected_rows > 1)
            $this->notice(sprintf(__('%1$s subscribers have been confirmed.',WYSIJA),$this->_affected_rows));
        else
            $this->notice(sprintf(__('%1$s subscriber have been confirmed.',WYSIJA),$this->_affected_rows));
        $this->defaultDisplay();
    }

    /**
     * bulk action copy to list
     * @global type $wpdb
     * @param type $data
     */
    /*function unsubscribemany(){
        $helperUser=WYSIJA::get('user','helper');
        foreach($_POST['wysija']['user']['user_id'] as $uid)    $helperUser->unsubscribe($uid,true);
        $count=count($_POST['wysija']['user']['user_id']);
        $this->notice(sprintf(__('%1$d Subscribers have been unsubscribed.',WYSIJA),$count));
        $this->redirect();
    }*/

    function lists(){
        $this->js[]='wysija-admin-list';
        $this->_commonlists();

        $this->modelObj=WYSIJA::get('list','model');
        $this->viewObj->title=__('Edit lists',WYSIJA);
        $this->modelObj->countRows=$this->modelObj->count();

        $this->viewObj->model=$this->modelObj;
        $this->data['form']=$this->_getForm();
    }

    function editlist(){
        $this->_commonlists();
        $this->data['form']=$this->_getForm($_REQUEST['id']);

        $this->viewObj->title=sprintf(__('Editing list %1$s',WYSIJA), '<b><i>'.$this->data['form']['name'].'</i></b>');
    }

    function addlist(){
        $this->_commonlists();
        $this->viewObj->title=__('How about a new list?',WYSIJA);
        $this->data['form']=$this->_getForm();
    }

    function duplicatelist(){

        /* get the list's email id
         * 0 duplicate the list's welcome email
         * 1 duplicate the list
         * 2 duplicate the list's subscribers
         */
        $model=WYSIJA::get('list','model');
        $data=$model->getOne(array('name','namekey','welcome_mail_id','unsub_mail_id'),array('list_id'=>(int)$_REQUEST['id']));

        $query='INSERT INTO `[wysija]email` (`created_at`,`campaign_id`,`subject`,`body`,`from_email`,`from_name`,`replyto_email`,`replyto_name`,`attachments`,`status`)
            SELECT '.time().',`campaign_id`,`subject`,`body`,`from_email`,`from_name`,`replyto_email`,`replyto_name`,`attachments`,`status` FROM [wysija]email
            WHERE email_id='.(int)$data['welcome_mail_id'];
        $emailWelcomeid=$model->query($query);


        $query='INSERT INTO `[wysija]email` (`created_at`,`campaign_id`,`subject`,`body`,`from_email`,`from_name`,`replyto_email`,`replyto_name`,`attachments`,`status`)
            SELECT '.time().',`campaign_id`,`subject`,`body`,`from_email`,`from_name`,`replyto_email`,`replyto_name`,`attachments`,`status` FROM [wysija]email
            WHERE email_id='.(int)$data['unsub_mail_id'];
        $emailUnsubid=$model->query($query);


        $query='INSERT INTO `[wysija]list` (`created_at`,`name`,`namekey`,`description`,`welcome_mail_id`,`unsub_mail_id`,`is_enabled`,`ordering`)
            SELECT '.time().',"'.stripslashes(__('Copy of ',WYSIJA)).$data['name'].'" ,"copy_'.$data['namekey'].time().'" ,`description`,'.$emailWelcomeid.','.$emailUnsubid.' ,1,`ordering` FROM [wysija]list
            WHERE list_id='.(int)$_REQUEST['id'];

        $listid=$model->query($query);

        $query='INSERT INTO `[wysija]user_list` (`list_id`,`user_id`,`sub_date`,`unsub_date`)
            SELECT '.$listid.',`user_id`,`sub_date`,`unsub_date` FROM [wysija]user_list
            WHERE list_id='.(int)$_REQUEST['id'];

        $model->query($query);

        $this->notice(sprintf(__('List "%1$s" has been duplicated.',WYSIJA),$data['name']));
        $this->redirect('admin.php?page=wysija_subscribers&action=lists');

    }

    function add($data=false){
        $this->js[]='wysija-validator';
        $this->viewObj->add=true;

        $this->title=$this->viewObj->title=__('Add Subscriber',WYSIJA);

        $this->data=array();
        $this->data['user']=false;
        if($data)$this->data['user']=$data;
        $modelList=WYSIJA::get('list','model');
        $modelList->limitON=false;
        $this->data['list']=$modelList->get(false,array('greater'=>array('is_enabled'=>'0') ));

    }

    function back(){
        $this->redirect();
    }

    function backtolist(){
        $this->redirect('admin.php?page=wysija_subscribers&action=lists');
    }

    function edit($id=false){

        if(isset($_REQUEST['id']) || $id){
            if(!$id) $id=$_REQUEST['id'];

            $this->js[]='wysija-validator';
            $this->js[]='wysija-charts';

            $this->data=array();
            $this->data['user']=$this->modelObj->getDetails(array('user_id'=>$id),true);
            if(!$this->data['user']){
                $this->notice(__('No subscriber found, most probably because he was deleted.',WYSIJA));
                return $this->redirect();
            }
            $modelList=WYSIJA::get('list','model');
            $modelList->limitON=false;
            $modelList->orderBy('is_enabled','DESC');
            $this->data['list']=$modelList->get(false,array('greater'=>array('is_enabled'=>'-1') ));

            //we prepare the data to be pased to the charts script
            $this->data['charts']['title']=' ';
            $this->data['charts']['stats']=array();

            //group email user stats by status where userid
            $modelEUS=WYSIJA::get('email_user_stat','model');
            $modelEUS->setConditions(array('equal'=>array('user_id'=>$id)));
            $query='SELECT count(email_id) as emails, status FROM `[wysija]'.$modelEUS->table_name."`";
            $query.=$modelEUS->makeWhere();
            $query.=' GROUP BY status';
            $countss=$modelEUS->query('get_res',$query,ARRAY_A);

            //-2 is an automatic unsubscribed made through bounce processing
            $statuses=array('-1'=>__('Bounced',WYSIJA),'0'=>__('Unopened',WYSIJA),'1'=>__('Opened',WYSIJA),'2'=>__('Clicked',WYSIJA),'3'=>__('Unsubscribed',WYSIJA) ,'-2'=>__('Unsubscribed',WYSIJA));
            foreach($countss as $count){
                $this->data['charts']['stats'][]=array('name'=>$statuses[$count['status']],'number'=>$count['emails']);
            }

            //email_user_url
            $modelEUU=WYSIJA::get('email_user_url','model');
            $modelEUU->setConditions(array('equal'=>array('user_id'=>$id)));
            $query='SELECT A.*,B.*,C.subject as name FROM `[wysija]'.$modelEUU->table_name."` as A JOIN `[wysija]url` as B on A.url_id=B.url_id JOIN `[wysija]email` as C on C.email_id=A.email_id ";
            $query.=$modelEUS->makeWhere();
            $query.=' ORDER BY A.number_clicked DESC ';
            $this->data['clicks']=$modelEUS->query('get_res',$query,ARRAY_A);

            foreach($this->data['clicks'] as $k => &$v){
                $v['url']=urldecode(utf8_encode($v['url']));
            }

            $chartsencoded=base64_encode(json_encode($this->data['charts']));
            wp_enqueue_script('wysija-admin-subscribers-edit-manual', WYSIJA_URL.'js/admin-subscribers-edit-manual.php?data='.$chartsencoded, array( 'wysija-charts' ), true);

            $this->viewObj->title=__('Edit',WYSIJA).' '.$this->data['user']['details']['email'];

        }else{
            $this->error('Cannot edit element primary key is missing : '. get_class($this));
        }

    }

    function deletelist(){
        $this->requireSecurity();

        /* get the list's email id
         * 0 delete the welcome email corresponding to that list
         * 1 delete the list subscribers reference
         * 2 delete the list campaigns references
         * 4 delete the list
         */
        $model=WYSIJA::get('list','model');
        $data=$model->getOne(array('name','namekey','welcome_mail_id'),array('list_id'=>(int)$_REQUEST['id']));

        if($data && isset($data['namekey']) && ($data['namekey']!='users')){

            //there is no welcome email per list that's old stuff
            //$modelRECYCLE=WYSIJA::get('email','model');
            //$modelRECYCLE->delete(array('email_id'=>$data['welcome_mail_id']));

            $modelRECYCLE=WYSIJA::get('user_list','model');
            $modelRECYCLE->delete(array('list_id'=>$_REQUEST['id']));

            $modelRECYCLE=WYSIJA::get('campaign_list','model');
            $modelRECYCLE->delete(array('list_id'=>$_REQUEST['id']));

            $model->reset();
            $model->delete(array('list_id'=>$_REQUEST['id']));

            $this->notice(sprintf(__('List "%1$s" has been deleted.',WYSIJA),$data['name']));
        }else{
            $this->error(__('The list does not exists or cannot be deleted.',WYSIJA),true);
        }

        $this->redirect('admin.php?page=wysija_subscribers&action=lists');

    }


    function synchlist(){
        $this->requireSecurity();

        $helperU=WYSIJA::get('user','helper');
        $helperU->synchList($_REQUEST['id']);

        $this->redirect('admin.php?page=wysija_subscribers&action=lists');
    }

    function synchlisttotal(){
        $this->requireSecurity();

        global $current_user;

        if(is_multisite() && is_super_admin( $current_user->ID )){
            $helperU=WYSIJA::get('user','helper');
            $helperU->synchList($_REQUEST['id'],true);
        }

        $this->redirect('admin.php?page=wysija_subscribers&action=lists');
    }


    function savelist(){
        $this->_resetGlobMsg();
        $update=false;

        if($_REQUEST['wysija']['list']['list_id']) $update=true;
        /* save the result */
        /* 1-save the welcome email*/
        /* 2-save the list*/
        if(isset($_REQUEST['wysija']['list']['is_public'])){
            if($_REQUEST['wysija']['list']['is_public']=='on')$_REQUEST['wysija']['list']['is_public']=1;
            else $_REQUEST['wysija']['list']['is_public']=0;
        }else{
            $_REQUEST['wysija']['list']['is_public']=0;
        }

        if($update){
            $this->modelObj->update($_REQUEST['wysija']['list']);
            $this->notice(__('List has been updated.',WYSIJA));
        }else{
            $_REQUEST['wysija']['list']['created_at']=time();
            $_REQUEST['wysija']['list']['is_enabled']=1;

            $this->modelObj->insert($_REQUEST['wysija']['list']);
            $this->notice(__('Your brand-new list awaits its first subscriber.',WYSIJA));
        }


        $this->redirect('admin.php?page=wysija_subscribers&action=lists');
    }



    function importpluginsave($id=false){
        $this->requireSecurity();
        $this->_resetGlobMsg();
        $modelConfig=WYSIJA::get('config','model');
        $importHelper=WYSIJA::get('import','helper');
        $pluginsImportable=$modelConfig->getValue('pluginsImportableEgg');
        $pluginsImported=array();
        foreach($_REQUEST['wysija']['import'] as $table_name =>$result){
            $connection_info=$importHelper->getPluginsInfo($table_name);

            if($result){
                $pluginsImported[]=$table_name;
                if(!$connection_info) $connection_info=$pluginsImportable[$table_name];
                $importHelper->import($table_name,$connection_info);
                sleep(2);
                $this->notice(sprintf(__('Import from plugin %1$s has been completed.',WYSIJA),"<strong>'".$connection_info['name']."'</strong>"));
            }else{
                $this->notice(sprintf(__('Import from plugin %1$s has been cancelled.',WYSIJA),"<strong>'".$connection_info['name']."'</strong>"));
            }

        }

        $modelConfig->save(array('pluginsImportedEgg'=>$pluginsImported));

        $this->redirect('admin.php?page=wysija_subscribers&action=lists');
    }

    function importplugins($id=false){
        $this->js[]='wysija-validator';

        $this->viewObj->title=__('Import subscribers from plugins',WYSIJA);

        $modelConfig=WYSIJA::get('config','model');

        $this->data=array();
        $this->data['plugins']=$modelConfig->getValue('pluginsImportableEgg');
        $importedOnes=$modelConfig->getValue('pluginsImportedEgg');

        if($importedOnes){
            foreach($importedOnes as $tablename){
                unset( $this->data['plugins'][$tablename]);
            }
        }


        if(!$this->data['plugins']){
            $this->notice(__('There is no plugin to import from.',WYSIJA));
            return $this->redirect();
        }
        $this->viewShow='importplugins';

    }

    function import($id=false){
        $this->js[]='wysija-validator';
        $this->viewObj->title=__('Import Subscribers',WYSIJA);
        $this->viewShow='import';
    }

    function importmatch(){
        $this->js[]='wysija-validator';
        $helperNumbers=WYSIJA::get('numbers','helper');
        $bytes=$helperNumbers->get_max_file_upload();

        if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH']>$bytes['maxbytes']){
            if(isset($_FILES['importfile']['name']) && $_FILES['importfile']['name']){
                $filename=$_FILES['importfile']['name'];
            }else{
                $filename=__("which you have pasted",WYSIJA);
            }

            $this->error(sprintf(__('Upload error, file %1$s is too large! (MAX:%2$s)',WYSIJA),$filename,$bytes['maxmegas']),true);
            $this->redirect('admin.php?page=wysija_subscribers&action=import');
            return false;
        }

        $importfields=get_option("wysija_import_fields");
        if(!$importfields) {
            $importfields=array(
                "fname"=>"firstname",
                "firstname"=>"firstname",
                "prenom"=>"firstname",
                "nom"=>"lastname",
                "name"=>"lastname",
                "lastname"=>"lastname",
                "lname"=>"lastname",
                "ipaddress"=>"ip",
                "ip"=>"ip",
                "addresseip"=>"ip",
            );
        }

        WYSIJA::update_option('wysija_import_fields',$importfields);

        $this->data=array();

        //is it a text import or a file import?*/
        if($_POST['wysija']['import']['type']=="copy"){
            if(!isset($_POST['wysija']['user_list']['csv'])){
                /* memory limit has been reached*/
                $this->error(__("The list you've pasted is too big for the browser. <strong>Upload the file</strong> instead.",WYSIJA),true);
                $this->redirect('admin.php?page=wysija_subscribers&action=import');
                return false;
            }
            $csv=trim(stripslashes($_POST['wysija']['user_list']['csv']));
        }else{
            //dbg($_FILES);
            //move_uploaded_file($_importfile, $destination);
            $csv=trim(file_get_contents($_FILES['importfile']['tmp_name']));
        }

        $csv=str_replace(array("\r","\n\n","\n\t\t\n\t\n\t\n\t\n","\n\t\t\n\t\n\t\n","\xEF\xBB\xBF","\n\t\n","\n(+1)"),array("\n","\n","\n ;","\n","",";",""),$csv);

        //this might be gmail recipients rare paste ...
         if(!preg_match_all('/<([a-z0-9_\'&\.\-\+])+\@(([a-z0-9\-])+\.)+([a-z0-9]{2,10})+>/i',$csv,$matches)){
              //return false;
         }else{

             if(substr($csv, -1)!=",")  $csv=trim($csv).",";

             $csv=str_replace(array(">, \r",">, \n",">,\r",">,\n",">, "),">,",$csv);
             $matchess=explode(">,",$csv);
             array_pop($matchess);

             if(count($matches[0])==count($matchess)){
                 //this is gmail simple paste
                 $csv=str_replace(array(">,","<"),array("\n",","),$csv);

             }
             $csv=trim($csv);
         }


        // try different set of enclosure and separator for the csv which can have different look depending on the data carried
        $fieldseparatorToTry=array(',',';',"\t");
        $fieldenclosurToTry=array('"','');
        $foundtheseparator=false;
        $foundtheenclosure='';
        $userHelper = WYSIJA::get("user","helper");
        foreach($fieldenclosurToTry as $enclosure){
            foreach($fieldseparatorToTry as $fsep){
                $csvArr = $this->_csvToArray($csv,4,$fsep,$enclosure);

                if((count($csvArr)>1 && count($csvArr[0])==count($csvArr[1]))){
                    if(count($csvArr[0])>1 || $userHelper->validEmail(trim($csvArr[0][0])) || $userHelper->validEmail(trim($csvArr[1][0]))){
                        $foundtheseparator=$fsep;
                        $foundtheenclosure=$enclosure;
                        break(2);
                    }

                }
            }
        }

         //if it is not a csv file we come out
        if(!$foundtheseparator){
            $this->notice(str_replace(array('[link]','[/link]'),array('<a href="#">','</a>'),__("The data you are trying to import doesn't appear to be in the CSV format (Comma Separated Values). [link]Read more[/link].",WYSIJA)));
            $this->notice(__('The first line of a CSV file should be the column headers : "email","lastname","firstname".',WYSIJA));
            $this->notice(__('The second line of a CSV file should be a set of values : "joeeg@example.com","Average","Joe".',WYSIJA));

            $this->notice(__("The two first lines of the file you've uploaded are as follow:",WYSIJA));

            $arraylines=explode("\n",$csv);

            if(empty($arraylines[0])) $text=__("Line is empty",WYSIJA);
            else $text=$arraylines[0];
            $this->notice("<strong>".$text."</strong>");

            if(empty($arraylines[1])) $text=__("Line is empty",WYSIJA);
            else $text=$arraylines[1];
            $this->notice("<strong>".$text."</strong>");

            $this->redirect('admin.php?page=wysija_subscribers&action=import');
            return false;
        }

        $csvArrMax = $this->_csvToArray($csv,0,$foundtheseparator,$foundtheenclosure);


        $this->data['totalrows']=count($csvArrMax);
        end($csvArrMax);
        $this->data['lastrow']=current($csvArrMax);
        $csvArrMax=null;

        $upload_dir = wp_upload_dir();

        //try to make a wysija dir to save the import file
        $fileHelp=WYSIJA::get('file','helper');
        $resultdir=$fileHelp->makeDir('import');
        if(!$resultdir) {
            $this->redirect('admin.php?page=wysija_subscribers&action=import');
            return false;
        }

        $filename='import-'.time().'.csv';
        $handle=fopen($resultdir.$filename, 'w');
        fwrite($handle, $csv);
        fclose($handle);

        $foundEmail=0;
        $keyemail=array();

        foreach($csvArr as $key => $csvrow){
            foreach($csvrow as $keyfield =>$val){
                if($userHelper->validEmail(trim($val))){
                    $foundEmail++;

                    $keyemail[$keyfield]=$csvArr[0][$keyfield];
                }
            }
        }

        $this->data['errormatch'] = false;
        if((count($csvArr)<2) || ((count($csvArr) -1) > $foundEmail)){
            $this->error(sprintf(__('There might be a problem with the list you are trying to import. We have identified %1$s emails out of %2$s rows.',WYSIJA),$foundEmail,count($csvArr)),true);
             $this->data['errormatch'] = true;
        }

        $this->data['csv'] = $csvArr;
        $dataImport=array(
            'csv'=>$filename,
            'fsep'=>$foundtheseparator,
            'fenc'=>$foundtheenclosure);
        $this->data['dataImport'] = base64_encode(serialize($dataImport));
        $this->data['keyemail'] = $keyemail;

        //test if the first row is data or not
        //test the email column
        foreach($this->data['keyemail'] as $k)    $keyemail=$k;

        $userHelper = WYSIJA::get("user","helper");


        if($userHelper->validEmail($keyemail)){
            $this->data['firstrowisdata']=true;
        }else{
            $this->data['totalrows']--;
        }

        $this->viewObj->title=__('Import Subscribers',WYSIJA);
        $this->viewShow='importmatch';

    }


    function import_save(){
        @ini_set('max_execution_time',0);

        $this->requireSecurity();
        $this->_resetGlobMsg();

        //to avoid timeout when importing a lot of data apparently.
        global $wpdb;
        $wpdb->query('set session wait_timeout=600');

        //import the contacts
        //1-check that a list is selected and that there is a csv file pasted
        //2-save the list if necessary
        //3-save the contacts and record them for each list selected

        //we need to save a new list in that situation
        if(isset($_REQUEST['wysija']['list'])){
            $model=WYSIJA::get('list','model');
            $data=array();
            $data['is_enabled']=1;
            $data['name']=$_REQUEST['wysija']['list']['newlistname'];
            $_REQUEST['wysija']['user_list']['list'][]=$model->insert($data);
        }

        if(!isset($_REQUEST['wysija']['user_list']['list']) || !$_REQUEST['wysija']['user_list']['list']){
            $this->error(__('You need to select at least one list.',WYSIJA),true);
            return $this->importmatch();
        }

        //is it a new list or not
        //try to make a wysija dir
        $csvData=unserialize(base64_decode($_REQUEST['wysija']['dataImport']));
        $csvfilename=$csvData['csv'];

        $fileHelp=WYSIJA::get('file','helper');
        $resultFile=$fileHelp->get($csvfilename,'import');
        if(!$resultFile){
            $upload_dir = wp_upload_dir();
            $this->error(sprintf(__('Cannot access CSV file. Verify access rights to this directory "%1$s"',WYSIJA),$upload_dir['basedir']),true);
            return $this->import();
        }

        //get the temp csv file
        $csvdata=file_get_contents($resultFile);

        $csvArr = $this->_csvToArray($csvdata,0,$csvData['fsep'],$csvData['fenc']);
        $datatoinsert=array();
        $emailKey='';
        foreach($_POST['wysija']['match'] as $key=> $val){
            if($val!='nomatch'){
                $datatoinsert[$key]=trim($val);
            }
            if($val == 'email'){
              $emailKey= $key;
            }
        }
        //dbg($datatoinsert);
        if(!in_array('status',$datatoinsert)){
            $datatoinsert['status']='status';
        }

        $queryStart='INSERT IGNORE INTO [wysija]user (`'.implode('` ,`',$datatoinsert).'`,`created_at`) VALUES ';

        //$linescount=count($csvArr);
        //detect the emails that are duplicate in the import file
        $emailsCount=array();

        $header_row=$csvArr[0];
        //we process the sql insertion 200 by 200 so that we are safe with the server
        $csvChunks=array_chunk($csvArr, 200);
        $csvArr=null;
        $j=0;
        $linescount=0;
        $dataNumbers=array('invalid'=>array(),'inserted'=>0,'outof'=>0,'list_added'=>0,'list_user_ids'=>0,'list_list_ids'=>count($_REQUEST['wysija']['user_list']['list']),'emails_queued'=>0);
        $ignored_row_count=0;
        foreach($csvChunks as $keyChunk =>$arra){
            foreach($arra as $keyline=> $emailline){
                if(isset($emailline[$emailKey])){
                   if(isset($emailsCount[$emailline[$emailKey]])) {
                        $emailsCount[$emailline[$emailKey]]++;
                        //$arra[$keyline]
                    }else{
                        $emailsCount[$emailline[$emailKey]]=1;
                    }
                }else{
                    //if the record doesn't have the attribute email then we just ignore it
                    $ignored_row_count++;
                    unset($arra[$keyline]);
                }
            }

            $result=$this->_importRows($queryStart,$arra,$j,$datatoinsert,$emailKey,$dataNumbers);

            if($result!==false) $j++;
            else{
                $try=0;
                while($result===false && $try<3){
                    $result=$this->_importRows($queryStart,$arra,$j,$datatoinsert,$emailKey,$dataNumbers);
                    $try++;
                }
                if($result){
                    $j++;
                }else{

                    $this->error(__('There seems to be an error with the list you\'re trying to import.',WYSIJA),true);
                    $this->redirect('admin.php?page=wysija_subscribers&action=import');
                    return false;
                }
            }
            $linescount=$linescount+$result;
        }

        if(!isset($_POST['firstrowisdata'])) {
            //save the importing fields to be able to match them the next time
            $importfields=get_option('wysija_import_fields');
            foreach($_POST['wysija']['match'] as $key=> $val){
                if($val!='nomatch') {
                    $importfields[$header_row[$key]]=$val;
                }
            }
            WYSIJA::update_option('wysija_import_fields',$importfields);
        }

        //get a list of list name
        $model=WYSIJA::get('list','model');
        $results=$model->get(array('name'),array('list_id'=>$_REQUEST['wysija']['user_list']['list']));

        $listnames=array();
        foreach($results as $k =>$v) $listnames[]=$v['name'];

        $helper_user=WYSIJA::get('user','helper');
        $helper_user->refreshUsers();

        foreach($emailsCount as $emailkeycount =>$countemailfile){
            if($countemailfile==1) unset($emailsCount[$emailkeycount]);
        }

        if($linescount<0)  $linescount=0;

        $dataNumbers['ignored']=($dataNumbers['outof']-$dataNumbers['inserted']);
        $dataNumbers['ignored_list']=(($dataNumbers['list_user_ids']*$dataNumbers['list_list_ids'])-$dataNumbers['list_added']);
        // $this->notice(sprintf(__('%1$s subscribers have been added to database. (%2$s were ignored)',WYSIJA),$dataNumbers['inserted'],$dataNumbers['ignored']));

        $ignored_row_count;//this contain some ignored row because the email attribute was not detected. I say there should be a message for those

        $this->notice(sprintf(__('%1$s subscribers added to %2$s.', WYSIJA),
                    $dataNumbers['list_user_ids'],
                    '"'.implode('", "',$listnames).'"'
                    ));

        if(count($emailsCount)>0){
            $listemails='';
            $m=0;
            foreach($emailsCount as $emailkeyalready => $occurences){
                if($m>0)$listemails.=', ';
                $listemails.= $emailkeyalready.' ('.$occurences.')';
                $m++;
            }
            //$emailsalreadyinserted=array_keys($emailsCount);
            $this->notice(sprintf(__('%1$s emails appear more than once in your file : %2$s.',WYSIJA),count($emailsCount),$listemails),0);
        }

        if(count($dataNumbers['invalid'])>0){
            $this->notice(sprintf(__('%1$s emails are not valid : %2$s.',WYSIJA),count($dataNumbers['invalid']),implode(', ',$dataNumbers['invalid'])),0);
        }

        $this->redirect();
    }

    function _importRows($query,$csvArr,$count,$datatoinsert,$emailKey,&$dataNumbers){
        $allEmails=array();
        $time=time();
        $linescount=count($csvArr);
        $nbfields=count($datatoinsert);

        foreach($csvArr as $k=> $line){
            if(!(count($line)>=(count($datatoinsert)-1))){

                unset($csvArr[$k]);
                $linescount--;
            }
        }

        $outof=0;
        $j=1;
        $helperUser=WYSIJA::get('user','helper');
        global $wpdb;
        foreach($csvArr as $kline=> $line){
            //dbg($csvArr,0);
            if(!isset($_POST['firstrowisdata']) && $j==1 && $count==0) {
            $j++;
            continue;
            }
            $i=1;
            $values='';
            if(isset($datatoinsert['status'])) $line['status']=1;
            foreach($line as $kl => &$vl){
                if(isset($datatoinsert[$kl])){
                    if($emailKey===$kl){
                        $vl=trim($vl);
                        if($helperUser->validEmail($vl)){
                            $allEmails[]=$vl;
                            $outof++;
                        }else{
                            $dataNumbers['invalid'][]=$vl;

                            unset($csvArr[$kline]);
                            $linescount--;
                            continue 2;
                        }
                    }
                    $values.="'".  mysql_real_escape_string($vl,$wpdb->dbh)."'";
                    if($nbfields>$i) $values.=',';
                    else $values.=','.$time;
                    $i++;
                }
            }

            $query.=" ($values) ";
            if($linescount>$j) $query.=',';
            $j++;

        }

        //replace query to import the subscribers
        $modelWysija=new WYSIJA_model();
        $resultqry=$modelWysija->query($query);

        //$outof=$linescount;
        global $wpdb;

        $linescount=$wpdb->rows_affected;
        $dataNumbers['inserted']+=$wpdb->rows_affected;
        $dataNumbers['outof']+=$outof;

        if($resultqry===false) {
            $this->error(__('Error when inserting emails.',WYSIJA),true);
            return false;
        }


        //select query to get all of there ids
        $user_ids=$this->modelObj->get(array('user_id'),array('email'=>$allEmails));
        $wpdb->rows_affected=0;

        //insert query per list
        $query='INSERT IGNORE INTO [wysija]user_list (`list_id` ,`user_id`,`sub_date`) VALUES ';
        $time_now=time();
        foreach($_REQUEST['wysija']['user_list']['list'] as $keyl=> $list_id){

            //for each list pre selected go through that process
            foreach($user_ids as $key=> $userid){

                //inserting each user id to this list
                $query.="($list_id,".$userid['user_id'].', '.$time_now.')';

                //if this is not the last row we put a comma for the next row
                if(count($user_ids)>($key+1)){
                    $query.=',';
                }
            }

            //if this is not the last row we put a comma for the next row
            if(count($_REQUEST['wysija']['user_list']['list'])>($keyl+1)){
                $query.=',';
            }
        }
        $resultqry2=$modelWysija->query($query);

        $dataNumbers['list_added']+=$wpdb->rows_affected;
        $dataNumbers['list_user_ids']+=count($user_ids);

        // take care of active follow ups retro-activity
        $helper_email=WYSIJA::get('email','helper');
        $follow_ups_per_list=$helper_email->get_active_follow_ups(array('email_id','params'),true);

        if(!empty($follow_ups_per_list)){
            foreach($_REQUEST['wysija']['user_list']['list'] as $list_id){
                //checking if this list has a list of follow ups
                if(isset($follow_ups_per_list[$list_id])){

                    //for each follow up of that list we queu an email
                    foreach($follow_ups_per_list[$list_id] as $key_queue=>$follow_up){
                        //insert query per active followup
                        $query_queue='INSERT IGNORE INTO [wysija]queue (`email_id` ,`user_id`,`send_at`) ';
                        $query_queue.=' SELECT '.$follow_up['email_id'].' , B.user_id , '.($time_now+$follow_up['delay']);
                        $query_queue.=' FROM [wysija]user_list as B';
                        $query_queue.=' WHERE B.list_id='.(int)$list_id.' AND sub_date='.$time_now;

                        $resultqry3=$modelWysija->query($query_queue);

                        $dataNumbers['emails_queued']+=$wpdb->rows_affected;
                    }
                }
            }
        }

        if($resultqry2===false) {
            $this->error(__('Error when inserting list.',WYSIJA),true);
            return false;
        }
        if($resultqry==0) return '0';
        return $linescount;
    }

    function export(){
        $this->js[]='wysija-validator';

        $this->viewObj->title=__('Export Subscribers',WYSIJA);
        $this->data=array();
        $this->data['lists']=$this->_getLists(false);
        $this->viewShow='export';
    }

    function exportcampaign(){
        if(isset($_REQUEST['file_name'])){
            $content=file_get_contents(base64_decode($_REQUEST['file_name']));
            $user_ids=explode(",",$content);
        }
        $_REQUEST['wysija']['user']['user_id']=$user_ids;

        $this->exportlist();
    }

    function exportlist(){
        $number=count($_REQUEST['wysija']['user']['user_id']);
        $this->viewObj->title=sprintf(__('Exporting %1$s subscribers',WYSIJA),$number);
        $this->data=array();
        $this->data['subscribers'] = $_REQUEST['wysija']['user']['user_id'];
        $this->data['user'] = $_REQUEST['wysija']['user'];//for batch-selecting
        $this->data['filter'] = $_REQUEST['wysija']['filter'];//for batch-selecting
        $this->viewShow='export';
    }



    function sendconfirmation(){
        $helperUser=WYSIJA::get('user','helper');
        $helperUser->sendConfirmationEmail($_POST['wysija']['user']['user_id']);
        $this->redirect();
    }

    /**
     * bulk delete option
     */
    function deleteusers(){
        $helper_user=WYSIJA::get('user','helper');
        if(!empty($this->_batch_select))
            $helper_user->delete($this->_batch_select, false, true);
        else
            $helper_user->delete($_POST['wysija']['user']['user_id']);
        if($this->_affected_rows > 1)
            $this->notice(sprintf(__(' %1$s subscribers have been deleted.',WYSIJA),$this->_affected_rows));
        else
            $this->notice(sprintf(__(' %1$s subscriber have been deleted.',WYSIJA),$this->_affected_rows));

        // make sure the total count of subscribers is updated
        $helper_user->refreshUsers();
        $this->redirect();
    }

    /**
     * function generating an export file based on an array of user_ids
     */
     /**
     * function generating an export file based on an array of user_ids
     */
    function export_get(){
        @ini_set('max_execution_time',0);

        //prepare the columns that need to be exported
        $model=WYSIJA::get('user_field','model');
        $fields=$model->getFields();
        $namefields=array();
        foreach($_POST['wysija']['export']['fields'] as $keyfield){
            $namefields[]=$fields[$keyfield];
        }

        //create the export file step by step
        $file_header=implode(';',$namefields)."\n";
         //generate temp file
        $fileHelp=WYSIJA::get('file','helper');
        $resultFile=$fileHelp->temp($file_header,'export','.csv');

        //open the created file in append mode
        $handle=fopen($resultFile['path'], 'a');

        //get a list of user_ids to export
        if(isset($_POST['wysija']['export']['user_ids'])
                && $_POST['wysija']['export']['user_ids']
                && empty($this->_batch_select)){
            $userids=unserialize(base64_decode($_POST['wysija']['export']['user_ids']));
            $this->write_data_to_export_file($handle,$userids);
            $useridsrows = count($userids);
            $userids = null;// free memory
        }else{
            //based on filters get a list of user_ids
            $userids = array();
            $useridsrows = 0;
            if(!empty($this->_batch_select)){ // batch select and export
                $useridsrows = $this->_batch_select['count'];
                $qry = $this->_batch_select['original_query'];
            }elseif(isset($_POST['wysija']['export']['filter']['list']) && $_POST['wysija']['export']['filter']['list']){ // select list(s) and export
                $where='';
                if(!is_array($_POST['wysija']['export']['filter']['list']))
                    $_POST['wysija']['export']['filter']['list'] = array($_POST['wysija']['export']['filter']['list']);
                if(isset($_POST['wysija']['export']['filter']['confirmed'])){
                    $where=' AND B.status>0 ';
                }
                $from = ' `[wysija]user_list` as A
                    JOIN `[wysija]user` as B on A.user_id=B.user_id';
                $where = ' A.list_id IN ('.implode(',',$_POST['wysija']['export']['filter']['list']).')'.$where;
                $qry='SELECT B.user_id FROM '.$from . ' WHERE ' .$where;
                $qry_count='SELECT COUNT(DISTINCT A.user_id) FROM '.$from . ' WHERE ' .$where;
            }else{ // export all list
                $from = '`[wysija]user` as A';
                $where = '1';
                if(isset($_POST['wysija']['export']['filter']['confirmed'])){
                    $where ='A.status>0';
                }
                $qry='SELECT A.user_id FROM '. $from . ' WHERE '. $where;
                $qry_count='SELECT COUNT(DISTINCT A.user_id) FROM '. $from . ' WHERE '. $where . ' ';
            }
            $user_ids_chunks = array(); // chunk rows into separated batchs, limit by $this->_export_batch
            $qry_batchs = array(); // store all batched queries
            if(empty($useridsrows)){
                $useridsrows_result = $this->modelObj->getResults($qry_count, ARRAY_N);
                $useridsrows = (int)$useridsrows_result[0][0];
            }

            if($useridsrows <= $this->_export_batch){
                $useridsdb=$this->modelObj->getResults($qry,ARRAY_N);
                foreach($useridsdb as $uarr){
                    $userids[]=$uarr[0];
                }
                $this->write_data_to_export_file($handle,$userids);
                $userids = null;// free memory
            }
            else{
                $pages = ceil($useridsrows / $this->_export_batch);//pagination
                for ($i = 0; $i < $pages; $i++) {
                    $qrybatch = $qry. ' ORDER BY user_id ASC LIMIT '.($i*$this->_export_batch) . ',' . $this->_export_batch;
                    $useridsdb=$this->modelObj->getResults($qrybatch,ARRAY_N);
                    foreach($useridsdb as $uarr){
                        $userids[]=$uarr[0];
                    }
                    $this->write_data_to_export_file($handle,$userids);
                    unset($userids);// free memory
                    unset($useridsdb);//free memory
                }
            }
        }





        fclose($handle);

        $url=get_bloginfo('wpurl').'/wp-admin/admin.php?page=wysija_subscribers&action=exportedFileGet&file='.base64_encode($resultFile['path']);
        $this->notice(str_replace(
                array('[link]','[/link]'),
                array('<a href="'.$url.'" target="_blank" class="exported-file" >','</a>'),
                sprintf(__('%1$s subscribers were exported. Get the exported file [link]here[/link].',WYSIJA),$useridsrows)));

        if(isset($_REQUEST['camp_id'])){
            $this->redirect('admin.php?page=wysija_campaigns&action=viewstats&id='.$_REQUEST['camp_id']);
        }else{
           $this->redirect();
        }
    }

    function write_data_to_export_file(&$handle, $userids){
        $user_ids_chunks=array_chunk($userids, 200);
	$userids = null;// free memory


        $modelUser=WYSIJA::get('user','model');
        foreach($user_ids_chunks as $userid_chunk){
            //get the full data for that specific chunk of ids
            $data=$modelUser->get($_POST['wysija']['export']['fields'],array('user_id'=>$userid_chunk));

            if(in_array('created_at', $_POST['wysija']['export']['fields'])){
                foreach($data as $key=>$row){
                    $data[$key]['created_at']=date_i18n(get_option('date_format'),$row['created_at']);
                }
            }

            //append content to the file
            foreach($data as $row){
                fwrite($handle, implode(';',$row)."\n");
            }
        }


    }


    function exportedFileGet(){
        if(isset($_REQUEST['file'])){
            $helper=WYSIJA::get('file','helper');
            $helper->send(base64_decode($_REQUEST['file']));
        }
    }

    /**
     *
     * @param type $input
     * @param type $rowstoread
     * @param type $delimiter
     * @param type $enclosure
     * @param type $linedelimiter
     * @return array
     */
    function _csvToArray($input,$rowstoread=0 , $delimiter=',',$enclosure='',$linedelimiter="\n"){
        $header = null;
        $data = array();

        $csvData = explode($linedelimiter,$input);
        $i=1;
        foreach($csvData as $csvLine){
            if($rowstoread!=0 && $i>$rowstoread) return $data;

            /* str_getcsv only exists in php5 ...*/
            if(!function_exists("str_getcsv")){
                $data[]= $this->csv_explode($csvLine, $delimiter,$enclosure);
            }else{
               $data[] = str_getcsv($csvLine, $delimiter,$enclosure);
            }

            $i++;
        }

        return $data;
    }

    function csv_explode($str,$delim, $enclose, $preserve=false){
      $resArr = array();
      $n = 0;
      if(empty($enclose)){
          $resArr = explode($delim, $str);
      }else{
          $expEncArr = explode($enclose, $str);
          foreach($expEncArr as $EncItem){
            if($n++%2){
              array_push($resArr, array_pop($resArr) . ($preserve?$enclose:'') . $EncItem.($preserve?$enclose:''));
            }else{
              $expDelArr = explode($delim, $EncItem);
              array_push($resArr, array_pop($resArr) . array_shift($expDelArr));
              $resArr = array_merge($resArr, $expDelArr);
            }
          }
      }

      return $resArr;
    }



    /*
     * common task to all the list actions
     */
    function _commonlists(){
        $this->js[]='wysija-validator';

        $this->data=array();
        $this->data['list']=$this->_getLists(10);

    }

    function _getLists($limit=false){

        $modelList=WYSIJA::get('list','model');
        $modelList->escapingOn=true;
        $modelList->_limitison=$limit;
        return $modelList->getLists();
    }

    function _getForm($id=false){
        if($id){
            $modelList=WYSIJA::get('list','model');

            return $modelList->getLists($id);
        }else{
            $array=array('name'=>'','list_id'=>'','description'=>'','is_public'=>true,'is_enabled'=>true);
            return $array;
        }

    }

    function cleanQueueFromAlreadySent(){
        $model_queue=WYSIJA::get('queue','model');
        $model_email=WYSIJA::get('email','model');
        $model_email->setConditions(array('type'=>2));
        $autonewsletter=$model_email->getRows(array('email_id','params'));
        $rows_affected=0;
        foreach ($autonewsletter as $data){
            $model_email->getParams($data);
            global $wpdb;
            if(isset($data['params']['autonl']['event']) && $data['params']['autonl']['event']=='subs-2-nl'){
                $query_queue='DELETE FROM [wysija]queue';
                $query_queue.=' WHERE email_id='.(int)$data['email_id'].' AND user_id ';
                $query_queue.='IN (SELECT B.user_id FROM [wysija]email_user_stat as B WHERE B.email_id='.(int)$data['email_id'].')';
                $result=$model_queue->query($query_queue);
                $rows_affected+=$wpdb->rows_affected;
            }

        }

        if($result){

            echo 'query successfully run<br/>';
            echo $rows_affected.' rows in the queue table have been deleted';
        }
        exit;
    }
}
