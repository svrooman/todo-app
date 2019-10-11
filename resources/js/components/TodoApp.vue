<template>
  <div>
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h2>Todos</h2>
        <div>
          <button class="btn btn-primary" data-toggle="modal" data-target="#todoModalCreateUpdate" @click="openModal('create', null)">Create a New ToDo</button>
        </div>
      </div>
      <div class="card-body">
        <div class="alert alert-success" role="alert" v-show="success === 'yes'">{{ alertMsg }}</div>
        <div class="list-group">
          <transition-group name="fade" tag="div">
            <div v-for="todo in todos" :key="todo.id" :class="{ 'list-group-item-light': todo.completion_status }" class="list-group-item">
              <div class="d-flex w-100 justify-content-between">
                <h5 class="mb-1">{{ todo.title }}</h5>
                <small>{{ todoStatus(todo) }}</small>
              </div>
              <p class="mb-1">{{ todo.notes }}</p>
              <div class="edit-buttons">
                <button class="btn btn-primary" data-toggle="modal" data-target="#todoModalCreateUpdate" @click="openModal('update', todo.id)">Update</button>
                <button class="btn btn-danger" data-toggle="modal" data-target="#todoModal" @click="openModal('delete', todo.id)">Delete</button>
              </div>
            </div>
          </transition-group>
        </div>
      </div>
    </div>
    <div id="todoModalCreateUpdate" class="modal fade" tabindex="-1" role="dialog" @closed="showModal = false">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modal.title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form
            id="create-update"
            @submit="onSubmit"
            method="post"
            novalidate="true"
          >
            <div class="modal-body"> 
              <div class="alert alert-danger" role="alert" v-show="success === 'no'">{{ alertMsg }}</div>
              <div class="form-group">
                <label for="title" class="col-form-label">* Title:</label>
                <input v-model="form.title" :class="{ 'is-invalid': isInValid('title') }" type="text" class="form-control" id="title" name="title" />
                <div class="invalid-feedback" v-html="errorMsg('title')"></div>
              </div>
              <div class="form-group">
                <label for="notes" class="col-form-label">Notes:</label>
                <textarea v-model="form.notes" class="form-control" id="notes" name="notes"></textarea>
              </div>
              <div class="form-group">
                <label for="due-date" class="col-form-label">Due Date:</label>
                <datepicker 
                  v-model="form.due_date" 
                  @selected="setDate" 
                  :disabled-dates="disabledDates" 
                  :bootstrap-styling="true" 
                  calendar-button-icon="fa fa-calendar" 
                  :clear-button="true"
                  id="due_date"
                  name="due_date" 
                />
              <div class="form-group" v-if="action === 'update'">
                <label for="completed_status" class="col-form-label">Mark as Complete:</label>
                <input v-model="form.completion_status" type="checkbox" class="form-control" id="completion_status" name="completion_status" />
              </div>
              </div>
            </div>
            <div class="modal-footer">
              <button type="submit" class="btn btn-primary">Save changes</button>
              <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <div id="todoModal" class="modal fade" tabindex="-1" role="dialog" @closed="showModal = false">
      <div class="alert alert-danger" role="alert" v-show="success === 'no'">{{ alertMsg }}</div>
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ modal.title }}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <p>{{ modal.body }}</p>
          </div>
          <div class="modal-footer">
            <button @click="onSubmit" type="submit" class="btn btn-danger">Delete</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Nope!</button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
  import Datepicker from 'vuejs-datepicker';
  const date = new Date();

  export default {
    name: "TodoApp",
    props: {

    },
    data: () => ({
      todos: [],
      currentToDoId: null,
      showModal: false,
      modal: {
        title: '',
        body: ''
      },
      form: {
        title: '',
        notes: '',
        due_date: ''
      },
      disabledDates: {
        to: new Date(date.getFullYear(), date.getMonth(), date.getDate())
      },
      success: 'maybe',
      errors: [],
      showTrans: false,
      action: '',
      alertMsg: 'Action completed successfully!'
    }),
    methods: {
      openModal(action, todoId) {
        let todoDataObj = null;
        this.success = 'maybe';
        this.currentToDoId = todoId;
        this.action = action;
        this.showModal = true;
        this.modal.title = action.charAt(0).toUpperCase() + action.slice(1);
        this.alertMsg = "TodDo " + action + "d successfully!"; 

        if (action === 'update') {
          todoDataObj = this.todos[this.findTodoArrayIndex()];
          this.form = JSON.parse(JSON.stringify(todoDataObj));
        }

        if (action === 'delete') {
          this.modal.body = "Are you sure you wish to " + action + " this ToDo?";
        }
      },
      async onSubmit(e) {
        e.preventDefault();

        let action = null;

        if (this.action === 'create') {
          action = this.create();
        }

        if (this.action === 'update') {
          action = this.update();
        }

        if (this.action === 'delete') {
          action = this.delete();
        }

        if (action) {
          action.catch(error => {
              this.errors = ( error.response.data.errors ? error.response.data.errors : [] );
              this.alertMsg = ( error.response.data.message ? error.response.data.message : 'Unknown Error :O' );
              this.success = 'no';
          });
        }
      },
      async fetch() {
        const todos = await this.$http.todos.fetch();
        this.todos = todos.data;
      },
      async create() {
        const todos = await this.$http.todos
          .store(this.form)
          .then(response => {
              this.success = 'yes';          
              $('#todoModalCreateUpdate').modal('hide');
              this.todos = [response.data].concat(this.todos);
          })
      },
      async update() {
        const todos = await this.$http.todos
          .update(this.form, this.currentToDoId)
          .then(response => {
              this.success = 'yes';      
              $('#todoModalCreateUpdate').modal('hide');
              this.todos[this.findTodoArrayIndex()] = response.data;
          });
      },
      async delete() {
        const todos = await this.$http.todos
          .delete(this.currentToDoId)
          .then(response => {
              this.success = 'yes';
              $('#todoModal').modal('hide');
              this.todos.splice(this.findTodoArrayIndex(), 1);
          });
      },
      isInValid: function (formInput) {
          return (!!this.errors[formInput]);
      },
      errorMsg: function (formInput) {
          return (this.isInValid(formInput) ? this.errors[formInput].join('<br />') : '');
      },
      setDate(selectedDate) {
        this.form.due_date = selectedDate;
      },
      clearVars() {
        this.showModal = false;
        this.form = {};
        this.errors = [];
        this.action = '';
      },
      findTodoArrayIndex() {
        return this.todos.findIndex(x => x.id === this.currentToDoId);
      },
      todoStatus(todo) {
        let data = "Due Date: ";

        if (todo.completion_status) {
          return "COMPLETED";
        }

        return "Due Date: " + (todo.due_date ? todo.due_date.slice(0, 10) : 'Whenevs!');
      }
    },
    components: {
      Datepicker
    },
    async mounted() {
      await this.fetch();
      
      $('#todoModalCreateUpdate').on('hidden.bs.modal', this.clearVars);
      $('#todoModal').on('hidden.bs.modal', this.clearVars);
    }
  };
</script>

<style>
  .fade-enter-active,
  .fade-leave-active {
    transition: opacity .5s;
  }

  .fade-enter,
  .fade-leave-to {
    opacity: 0
  }

  .edit-buttons {
    text-align: right;
  }

  .completed-text {
    color: #c7eed8;
    font-weight: 900;
  }
</style>
