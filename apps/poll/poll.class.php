<?php 
/**
 * @copyright 2008 Pierre Ducroquet
 *
 * @license http://www.gnu.org/licenses/gpl.html GNU Public License
 * See the enclosed file COPYING for license information.
 * 
 * @package applications
 **/

/** 
 * 
 * @package applications
 **/
class Poll extends Model
{
    public function build()
    {
        $userID = $this->currentUser->getID();
        if ((isset($_POST["voteAnswer"])) && ($userID > 0)) {
            $voteAnswer = filter_input(INPUT_POST, "voteAnswer", FILTER_VALIDATE_INT);
            /* As you can see, there is no extra check here against double votes.
            And it's the right way to do it©.
            The database engine is responsible for this.
            
            BTW, this query is safe since $userID is internal, and $voteAnswer has been filtered. */
            $this->db->query("INSERT INTO poll_votes (user, answer, poll) VALUES ($userID, $voteAnswer, (SELECT poll FROM poll_answers WHERE id=$voteAnswer))");
        }
        $canVote = false;
        $this->assign("canVote", false);
        try {
            $polls = $this->db->query("SELECT * FROM polls ORDER BY datetime desc LIMIT 1");
        } catch(PDOException $e) {
            Debug::kill($e->getMessage());
        }
        if ($pollInfos = $polls->fetch(PDO::FETCH_ASSOC)) {
            $this->assign("question", $pollInfos["question"]);
            $pollID = intval($pollInfos["id"]);
            
            // Can the user vote ?
            try {
                $votable = $this->db->prepare("SELECT COUNT(*) FROM poll_votes WHERE poll=:poll AND user=:user;");
                $votable->bindValue(":poll", $pollID);
                $votable->bindValue(":user", $userID);
                $votable->execute();
            } catch (PDOException $e) {
                Debug::kill($e->getMessage());
            }
            $votableResult = $votable->fetch();
            if ($votableResult[0] == 0) {
                $this->assign("canVote", true);
                $canVote = true;
            }
            
            if ($canVote) {
                // Get the answers list
                try {
                    $answers = $this->db->prepare("SELECT * FROM poll_answers WHERE poll=:poll;");
                    $answers->bindValue(":poll", $pollID);
                    $answers->execute();
                } catch (PDOException $e) {
                    Debug::kill($e->getMessage());
                }
                $answersTab = array();
                while ($answer = $answers->fetch(PDO::FETCH_ASSOC)) {
                    $id = $answer["id"];
                    $text = $answer["answer"];
                    $answersTab[$id] = $text;
                }
                $this->assign("answers", $answersTab);
            } else {
                // Get the results
                try {
                    $results = $this->db->query("SELECT pa.answer AS answer, COUNT(pv.id) AS votes FROM poll_answers pa LEFT JOIN poll_votes pv ON pv.answer = pa.id WHERE pa.poll=$pollID GROUP BY pa.id");
                } catch (PDOException $e) {
                    Debug::kill($e->getMessage());
                }
                $resultsTab = array();
                $countVotes = 0;
                while ($result = $results->fetch(PDO::FETCH_ASSOC)) {
                    $countVotes += intval($result["votes"]);
                    $answerText = $result["answer"];
                    $resultsTab[$answerText] = intval($result["votes"]);
                }
                $this->assign("results", $resultsTab);
                $this->assign("countVotes", $countVotes);
            }
		$commentSource = new CommentSource($this->db,"sondage".$pollID,$pollInfos["question"],"");
		$this->assign("commentId",$commentSource->getId());
        }
    }
}

?>
