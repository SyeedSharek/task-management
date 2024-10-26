<template>
    <h1>Roles & Permission List</h1>
    <router-link :to="{ name: 'PermissionCreate' }">
        <button class="btn btn-primary">Add Button</button>
    </router-link>

    <table class="table table-hover">
        <thead>
            <tr>
                <th scope="col">Sl</th>
                <th scope="col">Role Name</th>
                <th scope="col">Permission</th>
                <th scope="col">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr
                style="height: 100px"
                v-for="(role, index) in roles"
                :key="role.id"
            >
                <th scope="row">{{ index + 1 }}</th>
                <td>{{ role.name }}</td>
                <td>
                    <ul>
                        <li
                            v-for="(chunk, cIndex) in chunkPermissions(
                                role.permissions
                            )"
                            :key="cIndex"
                        >
                            <ul class="permission-row">
                                <li
                                    v-for="(permission, pIndex) in chunk"
                                    :key="pIndex"
                                >
                                    {{ permission.name }}
                                </li>
                            </ul>
                        </li>
                    </ul>
                </td>
                <td>
                    <router-link
                        :to="{
                            name: 'PermissionEdit',
                            params: { id: role.id },
                        }"
                        class="btn btn-primary m-1"
                        ><i class="fa-solid fa-pen-to-square"></i>
                    </router-link>
                    <a @click="destroy(role.id)" class="btn btn-danger"
                        ><i class="fa-solid fa-trash"></i
                    ></a>
                </td>
            </tr>
        </tbody>
    </table>
</template>
<script>
export default {
    data() {
        return {
            permissions: [],
            roles: [],
        };
    },
    mounted() {
        this.permissionList();
    },
    methods: {
        permissionList() {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .get("api/auth/role/index", {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((response) => {
                        this.roles = response.data.roles;
                    })
                    .catch((error) => {
                        console.log(error);
                    });
            }
        },

        chunkPermissions(permissions) {
            const chunked = [];
            for (let i = 0; i < permissions.length; i += 4) {
                chunked.push(permissions.slice(i, i + 4));
            }
            return chunked;
        },

        destroy(role_id) {
            const token = localStorage.getItem("authToken");
            if (token) {
                axios
                    .delete(`api/auth/role/delete/${role_id}`, {
                        headers: {
                            Authorization: `Bearer ${token}`,
                        },
                    })
                    .then((success) => {
                        this.permissionList();
                    })
                    .catch((error) => {
                        console.log(
                            "Error deleting role:",
                            error.response ? error.response.data : error
                        );
                    });
            } else {
                console.log("No auth token found.");
            }
        },

        PermissionEdit() {
            // Implement your own logic here
        },
    },
};
</script>
<style>
/* Styling for the permission rows */
.permission-row {
    display: flex;
    list-style: none;
    padding-left: 0;
    margin-bottom: 10px;
}

.permission-row li {
    margin-right: 10px; /* Add spacing between permission items */
}
</style>
