<?php
class ChifoumiPost extends FormModel
{
	public function build()
	{
		$bet = filter_input(INPUT_POST,"bet");
		$weapon = filter_input(INPUT_POST,"weapon");
		if(isset($bet) && isset($weapon)){
			$stmt = $this->db->prepare("
				INSERT INTO chifoumi(user,bet,weapon,dateofchallenge,challenged)
					SELECT 
						:user as user, 
						:bet as bet, 
						:weapon as weapon, 
						NOW() as dateofchallenge, 
						user_id as challenged
					FROM
						onlineusers
					WHERE
						user_id <> :user
					ORDER BY 
						RAND()
					LIMIT 1
			");
			$stmt->bindValue(":user",$this->currentUser->getID());
			$stmt->bindValue(":bet",$bet);
			$stmt->bindValue(":weapon",$weapon);
			$stmt->execute();
			
		}
	}
}
?>