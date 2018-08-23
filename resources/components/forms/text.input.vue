<template>
    <div class="input">
        <label class="input__label"> {{ label }}</label>
        <input class="input__field" :type="type" :placeholder="placeholder"
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
            onEnter: {
                type: Function,
                default: function() {},
            },
            type: {
                type: String,
                default: 'text',
            }
        }
    }
</script>