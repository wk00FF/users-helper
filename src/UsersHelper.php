<?
namespace wk00FF;
class UsersHelper{
	protected $ID;
	protected $fields=[];
	public function __construct(int $userID=0, array $fields=['*'], array $uf_fields=['UF*']){
		global $USER;
		if($userID){
			$this->ID=$userID;
		}
		else{
			$this->ID=$USER->GetID();
		}
		$this->fields=self::getByID($this->ID, $fields, $uf_fields);
	}
	public function getFields(array $fields=[]){
		if($fields){
			if(!is_array($fields)){
				if($fields!='') $fields=array($fields);
			}
			if(count($fields)>0){
				$ret=[];
				foreach($fields as $field){
					$ret[$field]=$this->getField($field);
				}
				return $ret;
			}
		}
		return $this->fields;
	}
	public function getField($field){
		return $this->fields[$field];
	}

	public static function getByID(int $userID=0, $fields=['*'], $uf_fields=['UF*']){
		global $USER;
		$ID=$userID?:$USER->GetID();
		$select=[];
		if(is_array($fields)){
			$select=array_merge($fields);
		}
		else{
			if($fields===false) $fields='*';
			if($fields!='') $select[]=$fields;
		}
		if(is_array($uf_fields)){
			$select=array_merge($uf_fields);
		}
		else{
			if($uf_fields===false) $uf_fields='UF*';
			if($uf_fields!='') $select[]=$uf_fields;
		}
		if(count($select)){
			$fields=\Bitrix\Main\UserTable::getList(['select'=>$select, 'filter'=>['ID'=>$ID]])->fetch();
			if(in_array('GROUPS', $select)){
				$fields['GROUPS']=\CUser::GetUserGroup($ID);
			}
		}
		return $fields?:false;
	}
	public static function setFieldsByID(int $userID=0, array $fields){
		global $USER;
		$ID=$userID?:$USER->GetID();
		$user=new \CUser;
		$user->Update($ID, $fields);
		if(!empty($fields['GROUPS'])){
			\CUser::SetUserGroup($ID, $fields['GROUPS']);
		}
		$strError.=$user->LAST_ERROR;
	}
}