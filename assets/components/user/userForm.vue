<template>
<div class="card">
    <div class="card-header">
        Create User
    </div>
    <div class="card-body">
        <div class="bd-example-snippet bd-code-snippet">
            <form v-if="dummyUser">
                <div class="bd-example m-0 border-0">
                    <div class="mb-3">
                        <label for="kundennummer" class="form-label">Kundennummer</label>
                        <input type="text" class="form-control" name="kundennummer" v-model="dummyUser.customerNumber" id="kundennummer" placeholder="1001000" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="anrede" class="form-label">Anrede</label>
                        <select class="form-select" aria-label="Default select example" v-model="dummyUser.anrede"  name="anrede" id="anrede" placeholder="Nothing Selected Yet">
                            <option value="mr">Mr</option>
                            <option value="ms">Ms</option>
                            <option value="company">Company</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="first_name" class="form-label">First Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" :class="{'is-invalid': v$.dummyUser.firstName.$error}" v-model="dummyUser.firstName" id="first_name" name="first_name" placeholder="First Name" >

                        <div v-for="error of v$.dummyUser.firstName.$errors">
                            <div class="text-danger" :error="error">Invalid name.</div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="second_name" class="form-label">Second Name</label>
                        <input class="form-control" id="second_name" v-model="dummyUser.secondName" name="second_name" placeholder="Second Name">
                    </div>
                    <div class="mb-3">
                        <label for="street" class="form-label">Street/ House number</label>
                        <div class="row">
                            <div class="col-4"><input type="text" v-model="dummyUser.street" class="form-control" id="street" name="street" placeholder="Street"></div>
                            <div class="col-8"><input type="text" v-model="dummyUser.houseNumber" class="form-control" id="house_number" name="house_number" placeholder="House Number"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="pin" class="form-label">Postleitzahl / Standort</label>
                        <div class="row">
                            <div class="col-8"><input type="text" v-model="dummyUser.pin" class="form-control" id="pin" name="pin" placeholder="Pin-code"></div>
                            <div class="col-4"><input type="text" v-model="dummyUser.location" class="form-control" id="location" name="location" placeholder="Location"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email address<span class="text-danger">*</span></label>
                        <input type="email" class="form-control" :class="{'is-invalid': v$.dummyUser.email.$error}"  v-model="dummyUser.email" id="email" name="email" placeholder="name@example.com">
                        <div v-for="error of v$.dummyUser.email.$errors">
                            <div class="text-danger" :error="error">Invalid email address. </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="card-footer text-body-secondary">
        <div class="d-flex justify-content-around p-2">
            <button type="button" class="btn btn-primary col-3" @click="save">Save</button>
            <button type="button" class="btn btn-primary col-3" @click="deleteUser" :disabled="!user.id">Delete</button>
            <button type="button" class="btn btn-primary col-3" @click="abort" >Abort</button>
        </div>
        <div class="text-center p-2 d-block d-md-none">
            <button type="button" class="btn btn-primary col-3" @click="goBack">Go Back</button>
        </div>
    </div>
</div>
</template>

<script>
import { useVuelidate } from '@vuelidate/core'
import { required, email } from '@vuelidate/validators'
import User from '../../models/User/User.js';

export default {
    setup () {
        return { v$: useVuelidate() }
    },
    props:{
        user: User,
        form: Object
    },
    data(){
        return {
            dummyUser:null,
        }
    },
    validations(){
        return {
            dummyUser:{
                firstName: { required },
                email: { required, email },
            }
        }
    },
    mounted(){
        this.setUser();
    },
    methods:{
        save(){
            this.v$.$touch();
            console.log("invalid",this.v$.$invalid)
            if (!this.v$.$invalid) {
                const props = this.user.id ? {
                    url:`user/update/${this.user.id}`,
                    method : 'PUT',
                    emit : 'userUpdated',
                    msg:"User updated successfully",
                } : {
                    url:'user/add',
                    method : 'POST',
                    emit : 'userAdded',
                    msg:"User created successfully",
                };

                return fetch(props.url, {
                    method: props.method,
                    headers: {
                    'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(this.dummyUser),
                })
                .then(result => {
                    // if (!result.ok) { throw new Error(`Failed to save user: ${result.statusText}`); }
                    const contentType = result.headers.get('Content-Type');
                    return contentType && contentType.includes('application/json') ? result.json() : result.text();
                })
                .then(result => {
                    if(typeof result === 'string'){
                        document.getElementById('app').innerHTML = result;
                    }else{
                        this.$emit(props.emit, new User(result.user));
                    }
                })
                .catch(error => {
                    console.error("There was an error!", error);
                });
            }
        },
        abort(){
            this.setUser();
        },
        deleteUser(){
            if(this.user.id){
                this.$emit('deleteUser', this.user.id);
            }
        },
        setUser(){
            this.dummyUser = JSON.parse(JSON.stringify(this.user));
            if(this.v$){
                this.v$.$reset();
            }
        },
        goBack(){
            this.$emit('goBack');
        }
    },
    watch:{
        user(){
            this.setUser();
        }
    }
}
</script>

<style>

</style>