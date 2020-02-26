<template>
    <v-app id="inspire">
        <v-navigation-drawer
                v-model="drawer"
                app
        >
            <v-list dense>
                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon>mdi-folder-multiple</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <router-link
                                :to="{name: 'departments'}"
                                tag="v-list-item-title"
                        >
                            Departments
                        </router-link>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon>mdi-folder-plus</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <router-link
                                :to="{name: 'create department'}"
                                tag="v-list-item-title"
                        >
                            New Department
                        </router-link>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon>mdi-folder-multiple</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <router-link
                                :to="{name: 'employees'}"
                                tag="v-list-item-title"
                        >
                            Employees
                        </router-link>
                    </v-list-item-content>
                </v-list-item>
                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon>mdi-folder-multiple</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <router-link
                                :to="{name: 'create employee'}"
                                tag="v-list-item-title"
                        >
                            New Employee
                        </router-link>
                    </v-list-item-content>
                </v-list-item>

                <v-list-item @click="">
                    <v-list-item-action>
                        <v-icon>mdi-lead-pencil</v-icon>
                    </v-list-item-action>
                    <v-list-item-content>
                        <router-link
                                :to="{name: 'tasks'}"
                                tag="v-list-item-title"
                        >
                            Tasks
                        </router-link>
                    </v-list-item-content>
                </v-list-item>
            </v-list>
        </v-navigation-drawer>

        <v-app-bar
                app
                color="indigo"
                dark
        >
            <v-app-bar-nav-icon @click.stop="drawer = !drawer"></v-app-bar-nav-icon>
            <v-toolbar-title>Application</v-toolbar-title>
        </v-app-bar>

        <v-content>
            <v-container
                    fluid
            >
                <router-view></router-view>

            </v-container>
        </v-content>
        <v-footer
                color="indigo"
                app
        >
            <span class="white--text">&copy; 2020</span>
        </v-footer>
    </v-app>
</template>

<script>
    export default {
        props: {
            source: String,
        },
        data: () => ({
            drawer: null,
        }),
        watch: {
            mute(val) {
                document.getElementById('inspire').className = val ? "on" : "";
            }
        }
    }
</script>