<?php
/**
 * Компонент проверки прав пользователей
 * 
 * Дата создания: 07.08.2014
 * Дата изменения: 08.08.2014
 **/
/**
 * 
 * @author 8600-90331
 * @deprecated
 */
class CheckAcccess extends CComponent
{
    
    /** 
        Функция возвращает массив с правами пользователей
        на разделы в зависимости от выбранного режима section->use_organization = true|false
            если true - возвращает права по организациям access_organization->right_(view|create|edit|delete)
            если false - возвращает права на текущий раздел access->right_(view|create|edit|delete) 
        Передаваемые параметры:
            $section_id (обязательный) - ИД Раздела (Section->id)
            $org_id (не обязательный, по умолчанию = 0) - ИД
        Результат:
            array('allow|deny'
                {,actions=array('index'|,'admin'|,'create'|,'edit'|,'delete')}
                ,users=>array('имя текущего пользователя')
            )
       @deprecated
    **/         
    public static function getAccessRight($section_id, $org_id=0)
    {               
        if (Yii::app()->user->role_admin) return array('allow', 'users'=>array(Yii::app()->user->name));
                
        $result_array = array('users'=>array(Yii::app()->user->name));
        $result_actions = array();
        $modelSection = Section::model()->findByPk($section_id);
        if (count($modelSection)==0) return $result_array;
                        
        $tableAccess = Access::model()->tableSchema->rawName;      
        $tableAccessOrganization = Yii::app()->db->tablePrefix.'access_organization';
        $tableGroupUser = Yii::app()->db->tablePrefix.'group_user';
            
        $model = Yii::app()->db->createCommand()
            ->select('t.id,t.date_create,t.date_modification,'
                .((!$modelSection->use_organization)?'t.right_view,t.right_create,t.right_edit,t.right_delete'
                :((($org_id==0)&&$modelSection->use_organization)?'a_o.right_view,':'a_o.right_view,').'a_o.right_view,a_o.right_create,a_o.right_edit,a_o.right_delete'))
            ->from('{{access}} t')
            ->leftJoin($tableAccessOrganization.' a_o', '[t].[id]=[a_o].[id_access]'
                .(!(($org_id==0)&&$modelSection->use_organization)?' AND [a_o].[id_organization]='.$org_id:''))
            ->where('[t].[id_section]='.$section_id.' AND ([t].[id_user]='.Yii::app()->user->id.' OR '
                .'[t].[id_group] IN (select [g_u].[id_group] from '.$tableGroupUser.' [g_u] '
                .'where [g_u].[id_user]='.Yii::app()->user->id.'))')
            ->queryAll();    
        
        foreach ($model as $value)
        {        
            if ($value['right_view']==true)
            {
                if (!in_array('index',$result_actions)) $result_actions[]='index';
                if (!in_array('admin',$result_actions)) $result_actions[]='admin';
            }
            
            if (!($modelSection->use_organization && $org_id==0))
            {
                if ($value['right_create']==true)
                {
                    if (!in_array('create',$result_actions)) $result_actions[]='create';
                }
                if ($value['right_edit']==true)
                {
                    if (!in_array('edit',$result_actions)) $result_actions[]='edit';
                }
                if ($value['right_delete']==true)
                {
                    if (!in_array('delete',$result_actions)) $result_actions[]='delete';
                }
            }                                         
        }                
        
        if (count($result_actions))
        {
            $result_array = array_merge($result_array, array('allow', 'actions' => $result_actions));
        }
        else
        {
            $result_array = array_merge($result_array, array('deny'));
        }        
        
        return $result_array;
        
    }
    
    
    
    
     
     public static function getTree($id=0, $parent_id=0)
     {        
        $data = array();
        $sql = "select  
                     t.id 
                    ,t.name 
                    ,t.use_organization 
                    ,case when exists(select * from p_func_tree_node_access(id,".Yii::app()->user->id.")) then 1 else 0 end as user_right
                    /*,case when exists(select 1 from {{access}} where id_tree=t.id and right_section_create=1 
                        and (id_user=".Yii::app()->user->id." or id_group in (select id_group from {{group_user}} 
                        where id_user=".Yii::app()->user->id.")) or 1=".Yii::app()->user->role_admin.") then 1 else 0 end u_r_s_c
                    ,case when exists(select 1 from {{access}} where id_tree=t.id and right_section_edit=1 
                        and (id_user=".Yii::app()->user->id." or id_group in (select id_group from {{group_user}} 
                        where id_user=".Yii::app()->user->id.")) or 1=".Yii::app()->user->role_admin.") then 1 else 0 end u_r_s_e
                    ,case when exists(select 1 from {{access}} where id_tree=t.id and right_section_delete=1 
                        and (id_user=".Yii::app()->user->id." or id_group in (select id_group from {{group_user}} 
                        where id_user=".Yii::app()->user->id.")) or 1=".Yii::app()->user->role_admin.") then 1 else 0 end u_r_s_d*/
                from {{tree}} t 
                where id_parent=$parent_id 
                    and exists(select 1 from p_func_tree_child_access(id, ".Yii::app()->user->id."))";
        $model = Yii::app()->db->createCommand($sql)->queryAll();   
        foreach ($model as $value)
        {
            /* + организации */
            $org = array();
            if ($value['use_organization'])
            {
                $sql_org = "select 
                                 t.id
                                ,t.name
                                ,case when exists(select 1 from {{access_organization}} a_o where (a_o.id_access in (
                                    select id from {{access}} where (id_user=".Yii::app()->user->id." or id_group in (
                                    select id_group from {{group_user}} where id_user=".Yii::app()->user->id.") 
                                    and id_tree=".$value['id'].") and id_organization=t.id) or 1=".Yii::app()->user->role_admin.")
                                    and a_o.right_section_create=1) then 1 else 0 end u_r_s_c  
                            from {{organization}} t 
                            where id in (select id_organization from {{tree_organization}} where id_tree=".$value['id']."
                                and (id_organization in (select id_organization from {{access_organization}} where id_access in (
                                    select id from {{access}} where id_user=".Yii::app()->user->id." or id_group in (
                                        select id_group from {{group_user}} where id_user=".Yii::app()->user->id." 
                                    )
                                ))
                                or 1=".Yii::app()->user->role_admin.")
                        )";
                $model_org = Yii::app()->db->createCommand($sql_org)->queryAll(); 
                foreach ($model_org as $value_org)
                {
                    $org[] = array(
                        'id'=>$value_org['id'], 
                        'text'=>'<i class="admin-icon-folder-open" style="margin-top:-10px; margin-left:3px;"></i>&nbsp;'
                            .'<span style="font-size:14px;">'.(($value['user_right']) 
                                ? CHtml::link($value_org['name'], array('/admin/section/admin', 
                                    'id_tree'=>$value['id'], 'id_org'=>$value_org['id'])).(($value_org['u_r_s_c']) ? '1' : '0')
                                : $value_org['name']).'</span>&nbsp',);
                }                
            }
            /* - организации */
            
            $data[] = array(
                'id'=>$value['id'], 
                'text'=>'<i class="admin-icon-folder-open" style="margin-top:-10px; margin-left:3px;"></i>&nbsp;'
                    .'<b><span style="font-size:14px;">'.(($value['user_right']) ? 
                        CHtml::link($value['name'], array('/admin/section/admin', 'id_tree'=>$value['id'])) : 
                        $value['name']).'</span></b>&nbsp',
                'children'=>self::getTree($id, $value['id']) + $org,                
            );
        }
        return $data;                       
     }          
     
     
     public static function checkAccessNode($id)
     {
        $returnAccess = -11;
        if ($model = Yii::app()->db->createCommand("select count(*) as res_access from p_func_tree_node_access($id,".Yii::app()->user->id.")")->queryAll())
            $returnAccess = $model[0]['res_access'];        
        return $returnAccess;
     }
     
     
     public static function getOrganizationList($id_parent=0,$level=0)
     {
               
        $data = array();
        $orgData = Yii::app()->db->createCommand("select * from {{organization}} where id_parent=$id_parent")
            ->queryAll();
        foreach ($orgData as $value)
        {
            $flag = false;
            $item = array();
            $user_right = Yii::app()->db->createCommand("select count(*) as res from {{user_organization}} where (id_user="
                .Yii::app()->user->id." and id_organization=".$value['id'] .") or 1=".Yii::app()->user->role_admin)->queryAll();
            if ($user_right['0']['res'])
            {                
                $item = array($value['id'] => str_repeat('--', $level).' '.$value['name']);
                $flag = true;
            }
            $data = $data + $item + self::getOrganizationList($value['id'], $flag?$level+1:$level);
        }
        return $data;
           
     } 
    
    
}        

