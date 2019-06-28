/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  8600-90-331
 * Created: 26.06.2019
 */

create table p_reg_ecr
(
	id int primary key identity,
	code_org varchar(5) not null,
	date_reg date not null,
	count_create int not null default 0,
	count_vote int not null default 0,
	avg_eval_a_1_1 int not null default 0,
	avg_eval_a_1_2 int not null default 0,
	avg_eval_a_1_3 int not null default 0,	
	author varchar(250) not null,
	date_create smalldatetime,
	date_update smalldatetime,
	date_delete smalldatetime,
)