<?php

namespace system\model\actions;

	interface Action{
		
		//----- PROPRIEDADES -----
		
		//----- CONSTANTES -----
		
		//----- FUNÇÕES -----
		
		/**
		 * Executa ação com a entity
		 * @param Entity $entity
		 * @param int $action
		 */
		public function exec($entity);
		
		/**
		 * Exporta stado da ação
		 * @return mixed
		 */
		public function status();

		/**
		 * Define cláusula where
		 *
		 * @param string where
		 */
		public function setWhere($where);
	}