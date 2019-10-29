<?php

class TiposAtendimentoListForm extends TPage {
    private $form, $table, $pagination, $loaded = FALSE;

    public function __construct() {
        parent::__construct();
        /** FORM */
        $this->form = new BootstrapFormBuilder('form_tipo_atendimento');
        $this->form->setFormTitle('Tipos de Atendimento');

        //Campos do formulário
        $id = new TEntry('id');
        $descricao = new TEntry('descricao');

        $id->setEditable(FALSE);
        $id->setSize('30%');
        $descricao->setSize('70%');
        
        //Validação dos campos
        $descricao->addValidation('Descrição', new TRequiredValidator);
        $descricao->addValidation('Descrição', new TMaxLengthValidator, array(255));
        
        // Adicionar os campos ao formulário
        $this->form->addFields([new TLabel('ID:')], [$id]);
        $this->form->addFields([new TLabel('Descrição')], [$descricao]);

        //Ações
        $btn = $this->form->addAction('Salvar', new TAction([$this, 'onSave']), 'fa:floppy-o');
        $btn->class = 'btn btn-sm btn-primary';
        $this->form->addAction('Limpar', new TAction([$this, 'onClear']), 'fa:eraser red');

        /** TABLE */
        $this->table = new BootstrapDatagridWrapper(new TDataGrid);
        $this->table->style = 'width: 100%';
        $this->table->setHeight(320);

        //Colunas
        $col_id = new TDataGridColumn('id', 'ID', 'center', 50);
        $col_descricao = new TDataGridColumn('descricao', 'Descrição', 'left');

        $this->table->addColumn($col_id);
        $this->table->addColumn($col_descricao);

        //Ações da tabela
        $col_id->setAction(new TAction([$this, 'onReload']), [['order' => 'id']]);
        $col_descricao->setAction(new TAction([$this, 'onReload']), [['order' => 'descricao']]);

        $acao_edit = new TDataGridAction([$this, 'onEdit']);
        $acao_edit->setButtonClass('btn btn-default');
        $acao_edit->setLabel('Editar');
        $acao_edit->setImage('fa:pencil-square-o blue fa-lg');
        $acao_edit->setField('id');
        $this->table->addAction($acao_edit);

        $acao_del = new TDataGridAction([$this, 'onDelete']);
        $acao_del->setButtonClass('btn btn-default');
        $acao_del->setImage('fa:trash-o red fa-lg');
        $acao_del->setLabel('Deletar');
        $acao_del->setField('id');
        $this->table->addAction($acao_del);

        $this->table->createModel();

        //PAGINAÇÃO
        $this->pagination = new TPageNavigation;
        $this->pagination->setAction(new TAction([$this, 'onReload']));
        $this->pagination->setWidth($this->table->getWidth());

        $panel = new TPanelGroup;
        $panel->add($this->table);
        $panel->addFooter($this->pagination);

        /**CONTAINER */
        $vbox = new TVBox;
        $vbox->style = 'width: 90%';
        $vbox->add(new TXMLBreadCrumb('menu.xml', __CLASS__));
        $vbox->add($this->form);
        $vbox->add($panel);

        parent::add($vbox);

    }

    public function onSave() {
        try {
            $this->form->validate();
            $tipo = $this->form->getData('TiposAtendimento');
            TTransaction::open('applicationSara');
            $tipo->store();
            $this->form->setData($tipo);
            TTransaction::close();
            new TMessage('info', 'Registro salvo com sucesso!');
            $this->onReload();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    public function onClear() {
        $this->form->clear();
    }
    public function onReload($param = NULL) {
        try {
            $this->table->clear();
            if(empty($param['order'])) {
                $param['order'] = 'id';
                $param['direction'] = 'asc';
            }
            TTransaction::open('applicationSara');
            $repo = new TRepository('TiposAtendimento');
            $criteria = new TCriteria;
            $limit = 10;

            $criteria->add(new TFilter('deleted_at', 'is', NULL));
            $criteria->setProperties($param);
            $criteria->setProperty('limit', $limit);

            $obj = $repo->load($criteria);

            if($obj) {
                foreach ($obj as $tipo) {
                    $this->table->addItem($tipo);
                }
            }
            
            $criteria->resetProperties();
            $count = $repo->count($criteria);
            $this->pagination->setCount($count);
            $this->pagination->setProperties($param);
            $this->pagination->setLimit($limit);

            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onEdit($param) {
        try {
            TTransaction::open('applicationSara');
            $tipo = TiposAtendimento::findById($param['id']);
            $this->form->setData($tipo);
            TTransaction::close();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
            TTransaction::rollback();
        }
    }
    public function onDelete($param) {
        $del = new TAction([$this, 'delete']);
        $del->setParameter('id', $param['id']);

        new TQuestion('Deseja realmente excluir ?', $del);
    }
    public function delete($param = NULL){
         try {
            TTransaction::open('applicationSara');
            $tipo = new TiposAtendimento;
            $tipo->onDelete($param['id']);
            TTransaction::close();
            new TMessage('info', 'Registro excluído com sucesso!');
            $this->onReload();
        } catch (Exception $e) {
            new TMessage('error', $e->getMessage());
        }
    }
    
    public function show() {
        if(!$this->loaded)
            $this->onReload();
        parent::show();
    }
}