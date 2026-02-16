<script setup>
import { ref } from 'vue';
import { Head, Link, router } from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import NavLink from '@/Components/NavLink.vue';
import Icon from '@/Shared/Icon.vue';


defineProps({
    title: String,
    auth: Object,
});

const showingNavigationDropdown = ref(false);
const sidebarCollapsed = ref(false);

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const logout = () => {
    router.post('/logout');
};

</script>

<template>
    <div>
        <Head :title="title" />

        <Banner />

        <div class="d-flex" style="min-height: 100vh;">
            <!-- Sidebar (Full Height from Top) -->
            <nav id="sidebar" :class="{'sidebar-collapsed': sidebarCollapsed}" style="position: fixed; top: 0; left: 0; height: 100vh; width: 250px; z-index: 1;">

            <!-- Top Navigation Bar (Remaining Width After Sidebar) -->
            <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm position-fixed" style="top: 0; left: 250px; width: calc(100% - 250px); height: 60px; z-index: 10;">
                <div class="container-fluid d-flex align-items-center">
                  
                 

                    <!-- Spacer to push profile to the right -->
                    <div class="flex-grow-1"></div>

                    <!-- Profile Dropdown (Right Side) -->
                    <div class="dropdown">
                        <button class="btn btn-link text-decoration-none d-flex align-items-center text-dark" type="button" id="profileDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <img v-if="$page.props.jetstream.managesProfilePhotos" class="rounded-circle me-2" :src="$page.props.auth.user.profile_photo_url" :alt="$page.props.auth.user.name" style="width: 32px; height: 32px;">
                            <div v-else class="bg-primary bg-opacity-25 rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                <Icon name="user" class="text-primary fs-6" />
                            </div>
                            <span class="fw-medium">{{ $page.props.auth.user.name }}</span>
                            <Icon name="chevron-down" class="ms-1" />
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="profileDropdown">
                            <li><h6 class="dropdown-header">Manage Account</h6></li>
                            <li><Link href="/profile" class="dropdown-item"><Icon name="user" class="me-2" />Profile</Link></li>
                            <li v-if="$page.props.jetstream.hasApiFeatures"><Link href="/api-tokens.index" class="dropdown-item"><Icon name="key" class="me-2" />API Tokens</Link></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form @submit.prevent="logout">
                                    <button type="submit" class="dropdown-item text-danger"><Icon name="logout" class="me-2" />Log Out</button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>
                <div class="sidebar-header position-relative">
                    <Link href="/dashboard" class="navbar-brand">
                        <ApplicationMark class="application-mark" />
                        <span v-if="!sidebarCollapsed" class="brand-text">CRMS</span>
                    </Link>
                    <button class="toggle-btn position-absolute top-0 end-0 m-2" @click="sidebarCollapsed = !sidebarCollapsed">
                        <Icon :name="sidebarCollapsed ? 'chevron-right' : 'chevron-left'" class="icon" />
                    </button>
                </div>
                <ul class="components">
                    <li>
                        <NavLink href="/dashboard" active="/dashboard" :class="{'active': $page.url === '/dashboard'}">
                            <Icon name="dashboard" class="icon" />
                            <span v-if="!sidebarCollapsed" class="nav-text">Dashboard</span>
                        </NavLink>
                    </li>
                    <li>
                        <NavLink href="/service_units" active="/service_units" :class="{'active': $page.url.startsWith('/service_units')}">
                            <Icon name="office" class="icon" />
                            <span v-if="!sidebarCollapsed" class="nav-text">Service Units</span>
                        </NavLink>
                    </li>
                    <li>
                        <NavLink href="/libraries" active="/libraries" :class="{'active': $page.url.startsWith('/libraries')}">
                            <Icon name="users" class="icon" />
                            <span v-if="!sidebarCollapsed" class="nav-text">Libraries</span>
                        </NavLink>
                    </li>
                </ul>
              
            </nav>

            <!-- Main Content Area -->
            <div class="flex-grow-1 bg-light" :style="{ marginLeft: sidebarCollapsed ? '70px' : '250px', marginTop: '60px', transition: 'margin-left 0.3s ease', minHeight: 'calc(100vh - 60px)', zIndex: '5', position: 'relative' }">

                <!-- Page Heading -->
                <header v-if="$slots.header" class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        <slot name="header" />
                    </div>
                </header>

                <!-- Page Content -->
                <main class="p-4">
                    <slot />
                </main>
            </div>
        </div>
    </div>
</template>




