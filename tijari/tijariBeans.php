<?php

    function escapeJavascript($str){
            return str_replace("\r\n", "<br>", str_replace("\"", "\\\"", str_replace("'", "\'",htmlspecialchars($str)))) ;	
    } 
	  
    class Vector {
        /*
         * The number of valid components in this Vector object.
         */
        protected $elementCount;
        /*
         * The array buffer into which the components of the vector are stored.
         */
        protected $elementData;



        /**Default vector Constructor*/
        function __construct()
        {
            $this->elementData = array();
            $this->elementCount = count($this->elementData);
        }



        /**Append the parameter element to the vector*/
        public function add($object)
        {

                $this->elementData[] = $object;
                $this->elementCount = count($this->elementData);
                return true;


        }

        /*
         * Returns true of Vector is empty false otherwise
         */
        public function isEmpty()
        {
            return($this->elementCount==0);
        }


      /*
       * Returns the current size of this Vector 
       */
       public function size()
       {
           return $this->elementCount;
       }

       /*
        *  Searches for the first occurence of the given argument
        **/
       public function indexOf($object)
       {
            $index = -1;
            for ($i = 0; $i < $this->elementCount; $i++)
            {
                            if ($this->elementData[$i]->equals($object) ){
                                            $index = $i;
                            }
            }

            return $index;

       }

       /*
        * This function will retain TRUE if $object is contained
        * within the vector else FALSE
        */

       public function contains($object)
       {
               return ($this->indexOf($object)>=0);
       }

       /*
        * Returns Vector Object at index $index
        * Error : Null is returned
        */

       public function get($index)
       {
               if($this->checkBound($index))
               {
                   return ($this->elementData[$index]);
               }
               return NULL;
       }

       /*
        * Sets the object at $index to be $object
        **/

       public function set($index,$object)
       {
               if($this->checkBound($index))
               {
                   $this->elementData[$index] =$object ;
                   return true;
               }
               return false;
       }

      /*
       * Removes element at $index
       **/ 

          public function removeAt($index) 
          {
           if ($this->checkBound($index)) 
               {
                     for ($i = $index; $i != $this->elementCount - 1; $i++)
                      {
                       $this->elementData[$i] = $this->elementData[$i +1];
                 }
               array_pop($this->elementData);
           $this->elementCount = count($this->elementData);

           return true;
           } 
           else {
                return false;
                }
          }

              /*
       * Removes Card from Hand
       **/ 

          public function removeCard($card) 
          {
           if ($this->contains($card)) 
               {
                     for ($i = 0; $i < $this->elementCount; $i++)
                      {
                       if ($card->getCardName() ==  $this->elementData[$i]->getCardName() ){
                            $this->removeAt($i);
                            $i = $this->elementCount;
                                            }
                                    }

                    return true;
           } 
           else {
                return false;
                }
          }

        /**
         * Removes all elements from the Vector.  Note that this does not
         * resize the internal data array.
         */
       public function removeAll()
       {
            $this->elementData = array();
            $this->elementCount = count($this->elementData);
       }


       /*
        * This function checks whether index
        * is within the array bound
        **/

       private function checkBound($index)
       {
             if($index > $this->elementCount-1 || $index < 0)
             {
                 throw new Exception('Array Index Out Of Bound Exception');
                 return false;
             }
             return true;
       }

        /*
         *Removes the first element from the Vector 
         */

        public function removeFirstElement()
        {
            if($this->elementCount==0)
            {
                throw new Exception('No Such Element');
            }
            $test =array_shift($this->elementData);
            if(!empty($test))
                $this->elementCount = count($this->elementData);
        }

    }
    
    class Tourney {        
        private $number   = 0;
        private $hostName = null;
        private $location = null;
        private $date     = null;
        private $time     = null;
        private $games    = null;

        function Tourney($hostName1, $location1, $date1, $time1)
        {
            $this->hostName = $hostName1;
            $this->location = $location1;
            $this->date     = $date1;      
            $this->time     = $time1; 
            $this->games    = new Vector;
        }    
        
	function getNumber()
	{
            return $this->number;
	}  
        
        function setNumber($n)
	{
            $this->number = $n;
	} 
        
        function getHostName()
	{
            return $this->hostName;
	}   
        function getLocation()
	{
            return $this->location;
	}
        function getDate()
	{
           return $this->date;
	}
        function getTime()
	{
            return $this->time;
	}    
        
        function getGames()
	{
            return $this->games;
	}
        
        function setGames($games1)
	{
            $this->games = $games1;
	}  
        
        function addGame($game1)
	{
            $this->games->add($game1);
	}        
    }  
    
    class Player {        
        private $firstName = null;
        private $lastName  = null;
        private $playerId  = 0;

        function Player($playerId1, $firstName1, $lastName1)
        {
            $this->firstName = $firstName1;
            $this->lastName  = $lastName1;
            $this->playerId  = $playerId1;      
        }    
           
        function getFirstName()
	{
		return $this->firstName;
	}
        function getLastName()
	{
		return $this->lastName;
	}     
        function getPlayerId()
	{
		return $this->playerId;
	}            
    }  
    
    class Team {        
        private $player1 = null;
        private $player2 = null;

        function Team($player1, $player2)
        {
            $this->player1 = $player1;
            $this->player2 = $player2;      
        }    
        
	function getPlayer1()
	{
            return $this->player1;
	} 
        
	function getPlayer2()
	{
            return $this->player2;
	}        
    }
    
    class Game {        
        private $team1           = null;
        private $team2           = null;
        private $team1TotalScore = 0;
        private $team2TotalScore = 0;
        private $subGames        = null;
        private $tourneyId       = 0;
        private $gameNumber      = 0;

        function Game($player11, $player12, $player21, $player22, $tourneyId1, $gameNumber1)
        {
            $this->team1       = new Team($player11, $player12);
            $this->team2       = new Team($player21, $player22); 
            $this->subGames    = new Vector;
            $this->tourneyId   = $tourneyId1;
            $this->gameNumber  = $gameNumber1; 
        }    
        
	function getTeam1()
	{
            return $this->team1;
	} 
        
	function getTeam2()
	{
            return $this->team2;
	}        
        
	function getTeam1TotalScore()
	{
            return $this->team1TotalScore;
	}  
        
        function getTeam2TotalScore()
	{
            return $this->team2TotalScore;
	}
        
	function setTeam1TotalScore($score1)
	{
            $this->team1TotalScore = $score1;
	}  
        
        function setTeam2TotalScore($score1)
	{
            $this->team2TotalScore = $score1;
	}        
        
        function getSubGames()
	{
            return $this->subGames;
	}   
        
        function getGameNumber()
	{
            return $this->gameNumber;
	}
        
        function addSubGame($subGame)
	{
            $this->subGames->add($subGame);
	} 
        
        function getTourneyId()
	{
            return $this->tourneyId;
	} 
              
    }
    
    class SubGame {        
        private $team1Score       = 0;
        private $team2Score       = 0;
        private $team1ScoreResult = null;
        private $team2ScoreResult = null;
        private $subGameNumber    = 0;
        private $gameId           = 0;

        function SubGame($subGameNumber1, $gameId1)
        {
            $this->subGameNumber = $subGameNumber1; 
            $this->gameId        = $gameId1;
        }    
                
	function getTeam1Score()
	{
            return $this->team1Score;
	}
        
	function getTeam2Score()
	{
            return $this->team1Score;
	}         
        
	function setTeam1Score($score)
	{
            $this->team1Score = $score;
	}
        
	function setTeam2Score($score)
	{
            $this->team2Score = $score;
	} 
        
        function getTeam1ScoreResult()
	{
            return $this->team1ScoreResult;
	}
        
        function getTeam2ScoreResult()
	{
            return $this->team2ScoreResult;
	}    
        
        function setTeam1ScoreResult($result1)
	{
            $this->team1ScoreResult = $result1;
	}
        
        function setTeam2ScoreResult($result1)
	{
            $this->team2ScoreResult = $result1;
	}       
        
        function getSubGames()
	{
            return $this->subGames;
	}   
        
        function getSubGameNumber()
	{
            return $this->subGameNumber;
	}
        
        function getGameId()
	{
            return $this->gameId;
	}        
    }    

?>