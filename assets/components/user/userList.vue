<template>
<div class="card">
    <div class="card-header">
        User List <button type="button" class="btn btn-sm btn-primary float-end d-block d-md-none" @click="addUser()" >Add</button>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-hover">
            <thead>
                <tr>
                    <th @click="orderChange('customerNumberOrder')" scope="col" class="cursor-pointer" >
                        <div class="d-flex justify-content-between">
                            <span>Kundennummer</span> 
                            <i class="fa" :class=orders[customerNumberOrder] aria-hidden="true"></i> 
                        </div>
                    </th>
                    <th @click="orderChange('firstNameOrder')" scope="col" class="cursor-pointer" >
                        <div class="d-flex justify-content-between">
                            <span>First Name</span> 
                            <i class="fa" :class=orders[firstNameOrder] aria-hidden="true"></i> 
                        </div>
                    </th>
                    <th @click="orderChange('secondNameOrder')" scope="col" class="cursor-pointer" >
                        <div class="d-flex justify-content-between">
                            <span>Second Name</span> 
                            <i class="fa" :class=orders[secondNameOrder] aria-hidden="true"></i> 
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                <tr class="cursor-pointer" v-for="user in proccesedUsers" @click="$emit('userSelected', user)">
                    <th scope="row">{{ user.customerNumber }}</th>
                    <td>{{ user.firstName }}</td>
                    <td>{{ user.secondName }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="card-footer d-flex justify-content-between">
        <div>Listing {{ proccesedUsers.length }}/{{ users.length }}</div>
        <div>
            <div class="btn-group btn-group-sm" v-if="users.length > numberOfUserPerList" role="group" aria-label="Small button group">
                <button type="button" class="btn btn-outline-primary" :disabled="currentPage === 1" @click="prev"> &lt; </button>
                <button type="button" class="btn btn-outline-primary" v-for="n in pages" @click="currentPage = n" :key="n"> {{ n }} </button>
                <button type="button" class="btn btn-outline-primary" :disabled="currentPage === pages" @click="next"> > </button>
            </div>
        </div>
        <div class="flex-shrink-1">
            <div class="input-group">
                <div class="input-group-text" id="btnGroupAddon">Users per page</div>
                <select type="text" class="form-control" style="width: 50px;" placeholder="10" v-model="numberOfUserPerList">
                    <option v-for="n in [5,10,20]" :key="n">{{ n }}</option>
                </select>
            </div>
        </div>
    </div>
</div>
</template>

<script>
import User from '../../models/User/User';

export default {
    props: {
        users: Array
    },
    data() {
        return {
            orders:['fa-sort','fa-sort-desc','fa-sort-asc'],
            customerNumberOrder: 0,
            firstNameOrder: 0,
            secondNameOrder: 0,
            numberOfUserPerList: 10,
            currentPage: 1
        }
    },
    mounted(){
    },
    methods:{
        addUser(){
            this.$emit('addUser', new User());
        },
        orderChange(prop){
            this[prop] = (this[prop] + 1) % 3;
        },
        next(){
            this.currentPage = this.currentPage >= this.pages ? this.pages : this.currentPage + 1;
        },
        prev(){
            this.currentPage = this.currentPage <= 1 ? 1 : this.currentPage - 1;
        }
    },
    computed:{
        proccesedUsers(){
            let sortedUsers = [...this.users]; 
            
            if (this.customerNumberOrder === 1) {
                sortedUsers.sort((a, b) => a.customerNumber.localeCompare(b.customerNumber));
            } else if (this.customerNumberOrder === 2) {
                sortedUsers.sort((a, b) => b.customerNumber.localeCompare(a.customerNumber));
            }
            
            if (this.firstNameOrder === 1) {
                sortedUsers.sort((a, b) => a.firstName.localeCompare(b.firstName));
            } else if (this.firstNameOrder === 2) {
                sortedUsers.sort((a, b) => b.firstName.localeCompare(a.firstName));
            }
            
            if (this.secondNameOrder === 1) {
                sortedUsers.sort((a, b) => a.secondName.localeCompare(b.secondName));
            } else if (this.secondNameOrder === 2) {
                sortedUsers.sort((a, b) => b.secondName.localeCompare(a.secondName));
            }

            const start = (this.currentPage - 1) * this.numberOfUserPerList;
            const end = this.currentPage * this.numberOfUserPerList;
            return sortedUsers.slice(start, end);
        },
        pages(){
            return Math.ceil(this.users.length/this.numberOfUserPerList);
        }
    }
}
</script>

<style>

</style>