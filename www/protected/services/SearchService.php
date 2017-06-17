<?php
require_once(APP_PATH.'/protected/vendors/osearch/CloudsearchClient.php');
require_once(APP_PATH.'/protected/vendors/osearch/CloudsearchIndex.php');
require_once(APP_PATH.'/protected/vendors/osearch/CloudsearchDoc.php');
require_once(APP_PATH.'/protected/vendors/osearch/CloudsearchSearch.php');
class SearchService extends Service{

    public function __construct(){
        $access_key = "CH79ggzPzBJsAbMN";
        $secret = "DKu53eAKAhkF10PjZMFcyANNC3Gsqw";
        $host = "http://opensearch-cn-beijing.aliyuncs.com";
        $key_type = "aliyun";
        $opts = array('host'=>$host);
        $this->client = new CloudsearchClient($access_key,$secret,$opts,$key_type);

    }

    public function addTopic($topics){
        $doc_obj = new CloudsearchDoc('topic',$this->client);
        $json = json_encode($topics);
        $res = $doc_obj->add($json,"main");
        return $res;
    }

    public function addMessage($message){
        $doc_obj = new CloudsearchDoc('message',$this->client);
        $json = json_encode($message);
        $res = $doc_obj->add($json,"main");
        return $res;
    }

    public function addPost($post){
        $doc_obj = new CloudsearchDoc('post',$this->client);
        $json = json_encode($post);
        $res = $doc_obj->add($json,"main");
        return $res;
    }

    public function addUser($users){
        $doc_obj = new CloudsearchDoc('user',$this->client);
        $json = json_encode($users);
        $res = $doc_obj->add($json,"main");
        return $res;
    }

    public function searchTopic($keywords,$page,$pagesize,$status='all'){
        $start = 0;
        if($page>=1){
            $start = ($page-1)*$pagesize;
        }
        $search_obj = new CloudsearchSearch($this->client);
        $search_obj->addIndex('topic');
        $search_obj->setQueryString("default:$keywords");
        if($status!=='all'){
            $search_obj->addFilter("status=$status");
        }
        $search_obj->setFormat("json");
        $json = $search_obj->search(array('start'=>$start,'hits'=>$pagesize));
        $result = json_decode($json,true);
        return $result;
    }

    public function searchMessage($keywords,$page,$pagesize,$status='all'){
        $start = 0;
        if($page>=1){
            $start = ($page-1)*$pagesize;
        }
        $search_obj = new CloudsearchSearch($this->client);
        $search_obj->addIndex('message');
        $search_obj->setQueryString("default:$keywords");
        if($status!=='all'){
            $search_obj->addFilter("status=$status");
        }
        $search_obj->setFormat("json");
        $json = $search_obj->search(array('start'=>$start,'hits'=>$pagesize));
        $result = json_decode($json,true);
        return $result;
    }

    public function searchPost($keywords,$page,$pagesize,$status='all'){
        $start = 0;
        if($page>=1){
            $start = ($page-1)*$pagesize;
        }
        $search_obj = new CloudsearchSearch($this->client);
        $search_obj->addIndex('post');
        if($status!=='all'){
            $search_obj->addFilter("status=$status");
        }
        $search_obj->setQueryString("default:$keywords");
        $search_obj->setFormat("json");
        $json = $search_obj->search(array('start'=>$start,'hits'=>$pagesize));
        $result = json_decode($json,true);
        return $result;
    }

    public function searchUser($keywords,$page,$pagesize){
        $start = 0;
        if($page>=1){
            $start = ($page-1)*$pagesize;
        }
        $search_obj = new CloudsearchSearch($this->client);
        $search_obj->addIndex('user');
        $search_obj->setQueryString("default:$keywords");
        $search_obj->setFormat("json");
        $json = $search_obj->search(array('start'=>$start,'hits'=>$pagesize));
        $result = json_decode($json,true);
        return $result;
    }
}
?>


