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
  },
  methods: {
    showAndHideForm(){
      this.mobileAdd = !this.mobileAdd
    }
  }
}
</script>