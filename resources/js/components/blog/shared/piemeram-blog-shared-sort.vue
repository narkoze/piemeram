<template>
  <th>
    <a @click="disabled || $emit('changed', column)">
      <slot></slot>
      <span v-if="sort === column">
        <i
          v-if="disabled"
          class="fas fa-spinner fa-pulse"
        >
        </i>
        <i
          v-else
          :class="['fas', {
            'fa-long-arrow-alt-up': direction === 'asc',
            'fa-long-arrow-alt-down': direction === 'desc'
          }]"
        >
        </i>
      </span>
    </a>
  </th>
</template>

<script>
  export default {
    props: [
      'column',
      'sort',
      'direction',
      'disabled'
    ]
  }
</script>
