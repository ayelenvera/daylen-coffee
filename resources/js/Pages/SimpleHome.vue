<template>
  <div>
    <h1>Welcome to Coffee Shop</h1>
    <p>This is a test page to check if Vue is working</p>
    <p v-if="$page.props.auth.user">
      Welcome back, {{ $page.props.auth.user.name }}
    </p>
    <div v-else>
      <Link :href="route('login')" class="text-blue-500">Login</Link> |
      <Link :href="route('register')" class="text-blue-500">Register</Link>
    </div>
  </div>
</template>

<script setup>
import { Link } from '@inertiajs/vue3';
</script>
