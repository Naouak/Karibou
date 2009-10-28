<?php
class ChifoumiPost extends Model
{
	public function build()
	{
		$result = Array();
		$result["posted"] = 0;
		$bet = filter_input(INPUT_POST,"bet");
		$weapon = filter_input(INPUT_POST,"weapon");
		if(isset($bet) && $bet > 0 && $bet <= 100000 && isset($weapon)){
			$stmt = $this->db->prepare("SELECT TIME_TO_SEC(TIMEDIFF(NOW(),dateofchallenge)) as diff FROM chifoumi WHERE user = :user ORDER BY id DESC LIMIT 1");
			$stmt->bindValue(":user",$this->currentUser->getID());
			$stmt->execute();
			$data = $stmt->fetch();
			if(empty($data) || $data['diff'] > 300){
				$stmt = $this->db->prepare("
					INSERT INTO chifoumi(user,bet,weapon,dateofchallenge,challenged)
						SELECT 
							:user as user, 
							:bet as bet, 
							:weapon as weapon, 
							NOW() as dateofchallenge, 
							ou.user_id as challenged
						FROM
							onlineusers ou
						LEFT JOIN
							chifoumi c ON c.user=ou.user_id AND c.acepted=0
						WHERE
							ou user_id <> :user
						GROUP BY
							ou.user_id
						ORDER BY 
							(COUNT(c.id)+1)*RAND() ASC
						LIMIT 1
				");
				
				$stmt->bindValue(":user",$this->currentUser->getID());
				$stmt->bindValue(":bet",$bet);
				$stmt->bindValue(":weapon",$weapon);
				$stmt->execute();
				$result["posted"] = 1;
			}
			
		}
		
		$this->assign("result",json_encode($result));
	}
}
?>
