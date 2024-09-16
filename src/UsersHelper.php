<?
namespace wk00FF;
class UsersHelper{
	protected $ID;
	protected $fields=[];

	/**
	 * @param int $userID		если его нет, то будет взят текущий юзер
	 * @param array $fields		$fields и $uf_fields: если false, то скачает все поля, если [] - то ниодного
	 * @param array $uf_fields
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public function __construct(int $userID=0, array $fields=['*'], array $uf_fields=['UF*']){
		global $USER;
		if($userID){
			$this->ID=$userID;
		}
		else{
			$this->ID=$USER->GetID();
		}
		$this->fields=self::getByID($this->ID, $fields, $uf_fields);
		return $this;
	}
	/**
	 * получить поля из объекта
	 * @param array $fields
	 * @return array|false|mixed|string[]
	 */
	public function getFields(array $fields=[]){
		if(count($fields)){
			$ret=[];
			foreach($fields as $field){
				$ret[$field]=$this->getField($field);
			}
			return $ret;
		}
		return $this->fields;
	}
	/**
	 * получить поле из объекта
	 * @param $field
	 * @return mixed|string
	 */
	public function getField($field){
		return $this->fields[$field];
	}
	/**
	 * получает значение полей юзера
	 * @param int $userID					если его нет, то будет взят текущий юзер
	 * @param array $fields					$fields и $uf_fields: если false, то скачает все поля, если [] - то ниодного
	 * @param array $uf_fields
	 * @return array|false|mixed|string[]	вернет массив с полями. Если в запрашиваемых полях есть GROUPS, там будут группы, в которых состоит юзер
	 * @throws \Bitrix\Main\ArgumentException
	 */
	public static function getByID(int $userID=0, array $fields=['*'], array $uf_fields=['UF*']){
		global $USER;
		$ID=$userID?:$USER->GetID();
		$select=[];
		if(is_array($fields)){
			$select=array_merge($fields);
		}
		if(is_array($uf_fields)){
			$select=array_merge($uf_fields);
		}
		if(count($select)){
			$fields=\Bitrix\Main\UserTable::getList(['select'=>$select, 'filter'=>['ID'=>$ID]])->fetch();
			if(in_array('GROUPS', $select)){
				$fields['GROUPS']=\CUser::GetUserGroup($ID);
			}
		}
		return $fields?:false;
	}
	/**
	 * запись данных
	 * @param int $userID
	 * @param array $fields		массив с полями. Если там есть GROUPS, то юзер будет назначен ан эти группы
	 * @return void
	 */
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