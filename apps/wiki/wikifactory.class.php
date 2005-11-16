<?php

class WikiFactory{

    static $db;
	
	function init($db)
	{
		self::$db = $db;
	} 
    
    static public function select($title, $latest = 'Y'){
        $sql  = "SELECT page_name, content, date, user_id ";
        $sql .= " FROM wiki "; 
        $sql .= " WHERE page_name = '".$title."'";
        $sql .= " AND latest = '".$latest."'";

        try
		{
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
		}
		catch( PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
        
		$row = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $wiki = new Wiki($row['page_name'], $row['content'], $row['date'], $row['user_id']);
        
        debug::display($sql);
        debug::display($row);
        
        return $wiki;
        
    }
    
    static public function insert($title, $content, $date, $user){
        
        if ( $title == '' ) {
            return false;
        }
        
        
        $sql2  = "UPDATE wiki SET latest = 'N'";
        $sql2 .= " WHERE page_name = '".$title."'";
        self::$db->exec($sql2);
        
        if ( !isset($date) || (isset($date) && $date == '') ){
            $date = date('Y-m-d H:i:s');
            debug::display($date);
        }
        
        $sql = "INSERT INTO wiki (page_name, content, user_id, date, latest) ";
        $sql .= "VALUES ('".$title."', '".$content."', '".$user."', '".$date."', 'Y');";
        
        self::$db->exec($sql);
    
         return true;
    } 
    
    static public function selectHistory($title) {
        $u = $GLOBALS['config']['bdd']["annuairedb"];
        $sql  = "SELECT w.page_name, w.content, w.date, w.user_id, u.login ";
        $sql .= " FROM wiki w, ".$u.".users u"; 
        $sql .= " WHERE page_name = '".$title."'";
        $sql .= " AND w.user_id = u.id";
        $sql .= " ORDER BY date"; 
        
        try
		{
			$stmt = self::$db->prepare($sql);
			$stmt->execute();
		}
		catch( PDOException $e)
		{
			Debug::kill($e->getMessage());
		}
        
		$row = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $row;

    }

}

?>
