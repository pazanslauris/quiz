<template>
    <div>
        <label class="input__label"> {{ label }}</label>
        <input class="input" type="text" :placeholder="placeholder"
               :value="value" @input="$emit('input', $event.target.value)"
               @keypress.enter="onEnter(value)"/>
    </div>
</template>

<script>
    export default {
        name: 'TextInput',
        props: {
            value: String,
            label: String,
            placeholder: String,
            onEnter: Function
        }
    }
</script>