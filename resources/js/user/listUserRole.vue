<template>
    <h1>List USer</h1>
    <table id="example" class="table table-striped" style="width: 100%">
        <thead>
            <tr>
                <th>sl</th>
                <th>Name</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody v-for="(user, index) in users" :key="index">
            <tr>
                <td>{{ index + 1 }}</td>
                <td>{{ user.name }}</td>
                <td>{{ user.email }}</td>
                <td v-for="(role, rIndex) in user.roles" :key="rIndex">
                    {{ role.name }}
                </td>
                <td>
                    <div
                        v-for="checkPermission in roleWiserPermissions"
                        :key="checkPermission.id"
                    >
                        <!-- <router-link
                        :to="{
                            name: 'Edit',
                            params: { id: task.id },
                        }"
                        class="btn btn-primary m-1"
                        ><i class="fa-solid fa-pen-to-square"></i>
                    </router-link> -->

                        <a
                            v-if="checkPermission.name == 'delete user'"
                            @click="destroy(user.id)"
                            class="btn btn-danger"
                            ><i class="fa-solid fa-trash"></i
                        ></a>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    data() {
        return {
            users: [],
            roleWiserPermissions: [],
        };
    },
    mounted() {
        this.userList();
        this.RoleWisePermission();
    },
    methods: {
        userList() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/user/index", {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.users = response.data.users;
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },
        destroy(id) {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .delete(`/api/auth/user/delete/${id}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((success) => {
                        this.userList(); // Update the list after successful deletion
                    })
                    .catch((error) => {
                        console.error(error);
                    });
            }
        },

        RoleWisePermission() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("/api/auth/user/rolewisepermission", {
                        headers: { Authorization: `Bearer ${token}` },
                    })
                    .then((response) => {
                        console.log(response.data.permissions);
                        this.roleWiserPermissions = response.data.permissions; // Store the permissions
                    })
                    .catch((error) => {
                        console.error("Error fetching permissions", error);
                    });
            }
        },
    },
};
</script>
<style>
.wrapper {
    margin-top: 5vh;
}

.dataTables_filter {
    float: right;
}

.table-hover > tbody > tr:hover {
    background-color: #ccffff;
}

@media only screen and (min-width: 768px) {
    .table {
        table-layout: fixed;
        max-width: 100% !important;
    }
}

thead {
    background: #ddd;
}

.table td:nth-child(2) {
    overflow: hidden;
    text-overflow: ellipsis;
}

.highlight {
    background: #ffff99;
}

@media only screen and (max-width: 767px) {
    /* Force table to not be like tables anymore */
    table,
    thead,
    tbody,
    th,
    td,
    tr {
        display: block;
    }

    /* Hide table headers (but not display: none;, for accessibility) */
    thead tr,
    tfoot tr {
        position: absolute;
        top: -9999px;
        left: -9999px;
    }

    td {
        /* Behave  like a "row" */
        border: none;
        border-bottom: 1px solid #eee;
        position: relative;
        padding-left: 50% !important;
    }

    td:before {
        /* Now like a table header */
        position: absolute;
        /* Top/left values mimic padding */
        top: 6px;
        left: 6px;
        width: 45%;
        padding-right: 10px;
        white-space: nowrap;
    }

    .table td:nth-child(1) {
        background: #ccc;
        height: 100%;
        top: 0;
        left: 0;
        font-weight: bold;
    }

    /*
  Label the data
  */
    td:nth-of-type(1):before {
        content: "Name";
    }

    td:nth-of-type(2):before {
        content: "Position";
    }

    td:nth-of-type(3):before {
        content: "Office";
    }

    td:nth-of-type(4):before {
        content: "Age";
    }

    td:nth-of-type(5):before {
        content: "Start date";
    }

    td:nth-of-type(6):before {
        content: "Salary";
    }

    .dataTables_length {
        display: none;
    }
}
</style>
