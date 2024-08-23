<template>
  <div>
    <div class="row">
      <div class="col-md-6 p-4" :class="{'d-none d-md-block': !mobileAdd}">
        <!-- <UserForm v-if="user"
          :user = user
          @userAdded="addUserToList"
          @deleteUser="deleteUserFromList"
          @userUpdated="updateUser"
          @goBack="showAndHideForm">
        </UserForm> -->
        <router-view></router-view>
      </div>
      <div class="col-md-6 p-4" :class="{'d-none d-md-block': mobileAdd}">
        <UserList/>
      </div>
    </div>
  </div>
</template>

<script>
import UserForm from './user/userForm.vue';
import UserList from './user/userList.vue';
import User from '../models/User/User.js';
import toster from '../utils/toster.js';

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
    showAndHideForm(){
      this.mobileAdd = !this.mobileAdd
    },
    selectUser(user) {
      this.$router.push(`/user/${user.id}`);
      this.user = user;
      this.showAndHideForm();
    },

    addUserToList(user, update = true) {
      this.users.push(user);
      if (update) {        
        toster.createToast('Success', 'User created successfully!');
        this.addNewUser();
        this.showAndHideForm();
      }
    },
    updateUser(user){
      const index = this.users.findIndex(u => u.id === user.id);
      if (index !== -1) {
        this.users[index] = user;
        toster.createToast('Success', 'User updated successfully!');
        this.addNewUser();
      }
    }
  }
}
</script>