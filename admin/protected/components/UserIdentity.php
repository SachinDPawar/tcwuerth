<?php
/** * UserIdentity represents the data needed to identity a user. * 
It contains the authentication method that checks if the provided *
 data can identity the user. */


class UserIdentity extends CUserIdentity
{
    private $id;
	private $rid;
 
    public function authenticate()
    {
        $record=Users::model()->findByAttributes(array('Username'=>$this->username));
        if($record===null)
            $this->errorCode=self::ERROR_USERNAME_INVALID;
        else if(strcmp($record->Password, crypt($this->password, $record->Password)))
            $this->errorCode=self::ERROR_PASSWORD_INVALID;
        else
        {
            $this->id=$record->Id;
			// $this->setState('Role', $record->userinroles[0]->role->Role); 
			//$this->rid=$record->userinroles[0]->RoleId;			
            $this->errorCode=self::ERROR_NONE;
        }
        return !$this->errorCode;
    }
 
    public function getId(){
        return $this->id;
    }
	
	 // public function getRole(){
        // return $this->rid;
    // }
}
?>