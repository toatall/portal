<?php

/**
 * ContactForm class.
 * ContactForm is the data structure for keeping
 * contact form data. It is used by the 'contact' action of 'SiteController'.
 */
class ContactForm extends CFormModel
{
    /**
     * @var string
     */
	public $name;	
	
	/**
	 * Тема
	 * @var string
	 */
	public $subject;
	
	/**
	 * Текст письма
	 * @var string
	 */
	public $body;

	/**
	 * Declares the validation rules.
	 * @return array
	 */
	public function rules()
	{
		return array(			
			array('subject, body', 'required'),
		);
	}
	
	/**
	 * Declares customized attribute labels.
	 * If not declared here, an attribute would have a label that is
	 * the same as its name with the first letter in upper case.
	 * @return array
	 */
	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
			'subject' => 'Тема',
			'body' => 'Сообщение',
		);
	}
}