<template>
  <div>
    <div class="row">
      <div class="col-md-6 p-4" :class="{'d-none d-md-block': !mobileAdd}">
        <UserForm v-if="user"
          :user = user
          @userAdded="addUserToList"
          @deleteUser="deleteUserFromList"
          @userUpdated="updateUser"
          @goBack="showAndHideForm">
        </UserForm>
      </div>
      <div class="col-md-6 p-4" :class="{'d-none d-md-block': mobileAdd}">
        <UserList 
          :users="users" 
          @userSelected="selectUser"
          @addUser="showAndHideForm">
        </UserList>
      </div>
    </div>
  </div>
</template>

<script>
import UserForm from './user/userForm.vue';
import UserList from './user/userList.vue';
import User from '../models/User/User.js';

export default { 
  components: {
    UserForm,
    UserList
  },
  data(){
    return {
      user:null,
      users:[],
      mobileAdd:false
    }
  },
  computed:{
  },
  mounted(){
    this.addNewUser();
    this.getUsers()
  },
  methods: {
    addNewUser(){
      this.user = new User();
    },
    showAndHideForm(){
      this.mobileAdd = !this.mobileAdd
    },
    getUsers(){
      console.log("Users will be list under this ");

      return fetch('user/list')
      .then(result => {
          if (result.headers.get('Content-Type') === 'application/json') {
              return result.json();
          } else {
              return result.text();
          }
      })
      .then((result)=>{
          console.log("Users will be list under this ");
          console.log(result);
          result.forEach(user => {
            this.addUserToList(new User(user), false);
          });
      })
    },

    deleteUserFromList(id){
      console.log(id);
      return fetch(`user/${id}`, { method: 'DELETE' })
      .then(result => {
        if (!result.ok) {
          throw new Error(`Failed to delete user with id ${id}`);
        }
        return result.json();
      })
      .then(() => {
        this.users = this.users.filter(user => user.id !== id);
        this.addNewUser();
      })
      .catch(error => {
        console.error('Error:', error);
      });
    },
    selectUser(user) {
      this.user = user;
      this.showAndHideForm();
    },

    addUserToList(user, update = true) {
      this.users.push(user);
      if (update) {        
        this.addNewUser();
        this.showAndHideForm();
      }
    },
    updateUser(user){
      const index = this.users.findIndex(u => u.id === user.id);
      if (index !== -1) {
        this.users[index] = user;
        this.addNewUser();
      }
    }
  }
}
</script>