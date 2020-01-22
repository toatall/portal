<?php

class MentorPostSearch extends MentorPost
{
    
    public function search() 
    {
        $criteria = new CDbCriteria;
        
        $criteria->compare('id', $this->id);                        
        $criteria->compare('id_mentor_ways', $this->id_mentor_ways);
        $criteria->compare('id_organization', $this->id_organization);
        $criteria->compare('title', $this->title, true);
        $criteria->compare('message1', $this->message1, true);
        $criteria->addCondition('date_delete is null');
        
        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
            'sort' => array(
                'defaultOrder' => 'date_create desc',
            ),
        ));
    }   
    
}

