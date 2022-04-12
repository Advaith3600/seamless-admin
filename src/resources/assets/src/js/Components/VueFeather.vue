<script>
import { defineComponent, h } from 'vue';
export default defineComponent({
    name: 'VueFeather',
    props: {
        animation: {
            type: String,
            default: undefined,
        },
        animationSpeed: {
            type: String,
            default: undefined,
        },
        fill: {
            type: String,
            default: 'none',
        },
        size: {
            type: [Number, String],
            default: 24,
        },
        stroke: {
            type: String,
            default: 'currentColor',
        },
        strokeLinecap: {
            type: String,
            default: 'round',
        },
        strokeLinejoin: {
            type: String,
            default: 'round',
        },
        strokeWidth: {
            type: [Number, String],
            default: 2,
        },
        tag: {
            type: String,
            default: 'i',
        },
        className: {
            type: String,
            default: ''
        },
        type: {
            type: String,
            default: 'feather',
            validator(type) {
                if (!window.feather)
                    throw new Error('The Feather icons is required.');

                if (!window.feather.icons[type])
                    throw new Error(`"${type}" is not an available icon type.`);

                return true;
            },
        },
    },
    computed: {
        isRemSize() {
            return typeof this.size === 'string' && this.size.endsWith('rem');
        },
    },
    render() {
        const {
            animation,
            animationSpeed,
            isRemSize,
            size,
            type,
        } = this;
        const icon = window.feather.icons[type];
        return h(
            this.tag,
            {
                ...this.$attrs,
                'data-name': type,
                'data-tags': icon.tags,
                'data-type': type,
                class: {
                    'vue-feather': true,
                    [`vue-feather--${type}`]: type,
                    [`vue-feather--${animation}`]: animation,
                    [`vue-feather--${animationSpeed}`]: animationSpeed,
                },
                style: isRemSize ? {
                    height: size,
                    width: size,
                } : undefined,
            },
            [
                h(
                    'svg',
                    {
                        ...icon.attrs,
                        fill: this.fill,
                        height: isRemSize ? undefined : size,
                        stroke: this.stroke,
                        'stroke-linecap': this.strokeLinecap,
                        'stroke-linejoin': this.strokeLinejoin,
                        'stroke-width': this.strokeWidth,
                        width: isRemSize ? undefined : size,
                        class: [icon.attrs.class, this.className, 'vue-feather__content'],
                        innerHTML: icon.contents,
                    },
                ),
            ],
    );
    },
});
</script>

<style lang="scss">
@keyframes vue-feather--spin {
    from {
        transform: rotate(0);
    }
    to {
        transform: rotate(360deg);
    }
}
.vue-feather {
    display: inline-block;
    overflow: hidden;
    &--spin {
        animation: vue-feather--spin 2s linear infinite;
    }
    &--pulse {
        animation: vue-feather--spin 2s infinite steps(8);
    }
    &--slow {
        animation-duration: 3s;
    }
    &--fast {
        animation-duration: 1s;
    }
    &__content {
        display: block;
        height: inherit;
        width: inherit;
    }
}
</style>
