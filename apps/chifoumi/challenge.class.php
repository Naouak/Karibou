<?php
class ChifoumiChallenge extends Model
{
	public function build()
	{
		//Pour fournir le JSON à la fin
		$result = Array();
	
		$weapon = $this->args["weapon"];
		$id = $this->args["id"];
		if(empty($id) || empty($weapon)){
			$weapon = filter_input(INPUT_POST,"weapon");
			$id = filter_input(INPUT_POST,"id");
		}
		if(!empty($id) && ($weapon) >= 0 && $weapon <= 3 ){
			$result["challenge"] = "yes";
			$stmt = $this->db->prepare("SELECT * FROM chifoumi WHERE id = :id LIMIT 1");
			$stmt->bindValue(":id",$id);
			$stmt->execute();
			
			$stmt2 = $this->db->prepare("UPDATE chifoumi SET defense = :def, acepted = :accepted, dateofresponse = NOW() WHERE id = :id");
			$stmt2->bindValue(":id",$id);
			$stmt2->bindValue(":def",$this->args["weapon"]);
			$stmt2->bindValue(":accepted",($this->args["weapon"] != 3));
			$stmt2->execute();
			
			$data = $stmt->fetch();
			
			
			if($weapon == 3){
				$result["result"] = -2;
			}
			elseif( ($weapon == 2 && $data["weapon"] == 1) || 
				($weapon == 1 && $data["weapon"] == 0) ||
				($weapon == 0 && $data["weapon"] == 2)){
				//Gain du challengé
				ScoreFactory::stealScoreFromUser($this->currentUser,$this->userFactory->prepareUserFromId($data['user']), $data["bet"], "chifoumi");
				
				$result["result"] = 1;
			}
			elseif($weapon == $data["weapon"]){
				//égalité
				$result["result"] = 0;
			}
			else{
				//Gain du challengeur
				ScoreFactory::stealScoreFromUser($this->userFactory->prepareUserFromId($data['user']),$this->currentUser, $data["bet"], "chifoumi");
				$result["result"] = -1;
			}
		}
		
		$stmt = $this->db->prepare("SELECT id,bet FROM chifoumi WHERE challenged = :user AND acepted = 0");
		$stmt->bindValue(":user",$this->currentUser->getID());
		$stmt->execute();
		$data = $stmt->fetchAll();

		foreach($data as $value){
			array_push($result,Array("id" => $value['id'],"bet" => $value['bet']));
		}

		//On fourni le JSON
		$this->assign("result",json_encode($result));
		
	}
}
?>