
export const BaseInput = {
    props: {
        modelValue: [String, Number],
        label: String,
        placeholder: String,
        type: {
            type: String,
            default: "text",
        },
        error: String,
        options: {
            type: Array,
            default: () => [],
        },
        col: String,
        required: {
            type: Boolean,
            default: false,
        },
        readonly: {
            type: Boolean,
            default: false,
        },
        hide: {
            type:Boolean,
            default: false,
        }
    },
    emits: ["update:modelValue", "change"],
    computed: {
        inputValue: {
            get() {
                return this.modelValue;
            },
            set(val) {
                this.$emit("update:modelValue", val);
            },
        },
    },
    methods: {
        emitSelectChange() {
            this.$emit('change', this.inputValue);
        }
    },
    setup(props, { emit }) {
        const fileToBase64 = (file) => {
            return new Promise((resolve, reject) => {
                const reader = new FileReader();
                reader.onload = () => resolve(reader.result);
                reader.onerror = reject;
                reader.readAsDataURL(file); // convert to base64
            });
        };

        const handleFileUpload = async (event) => {
            const file = event.target.files[0];
            if (!file) return;

            // Daftar tipe yang diizinkan
            const allowedTypes = ["image/", "application/pdf", "application/x-pdf"];

            // Cek MIME type
            const isValidMime = allowedTypes.some(type => file.type.toLowerCase().startsWith(type));

            // Cek ekstensi PDF sebagai fallback (untuk browser yang MIME kosong)
            const isPdfExt = file.name.toLowerCase().endsWith(".pdf");

            // Tolak file kalau bukan image dan bukan PDF
            if (!isValidMime && !isPdfExt) {
                alert("Only image or PDF files are allowed!");
                return;
            }

            // File valid, lanjut convert ke base64
            const base64 = await fileToBase64(file);
            const raw = base64.split(",")[1];
            const mimeType = base64.split(";")[0].replace("data:", "");

            // Preview (image langsung, PDF bisa embed)
            const previewBase64 = `data:${mimeType};base64,${raw}`;

            emit("update:modelValue", raw);
            emit("change", raw);

        };

        return {
            handleFileUpload,
            fileToBase64,
        };
    },
    template: `
    <div v-if="hide == false" :class="'col-'+col" class="mb-2">
    <div class="form-group">
        <label v-if="label" class="labelFormKs"> <span v-if="required" class="text-danger">* </span>
            <strong><small>{{ label }}</small></strong>
        </label>
        <input class="form-control form-control-sm" :required="required"
            v-if="type === 'text' || type === 'email' || type === 'tel' || type === 'number'"
            :type="type"
            :placeholder="placeholder"
            :readonly="readonly"
            v-model="inputValue"
        />

        <textarea class="form-control form-control-sm" :required="required"
            v-else-if="type === 'textarea'"
            :placeholder="placeholder"
            :readonly="readonly"
            v-model="inputValue"
        ></textarea>

        <select class="form-control form-control-sm"
                :required="required"
                v-else-if="type === 'select'"
                v-model="inputValue"
                @change="emitSelectChange">
            <option disabled value="">-- Pilih --</option>
            <option v-for="opt in options" :value="opt.value">{{ opt.label }}</option>
        </select>

        <input class="form-control form-control-sm" :required="required"
            v-else-if="type === 'date'"
            :type="type" 
            :readonly="readonly"
            v-model="inputValue"
        />

        
        <input class="form-control form-control-sm" :required="required"
            v-else-if="type === 'file'"
            :type="type" 
            v-model="inputValue"
        />

        <input
            v-else-if="type === 'input_image'"
            class="form-control form-control-sm"
            type="file"
            accept="image/*,application/pdf,.pdf"
            @change="handleFileUpload($event)"
        />
        
        <label v-else-if="type === 'label'" class="form-control form-control-sm">{{ inputValue }}</label>
        
        <input class="form-control form-control-sm" :required="required"
            v-else
            :type="type"
            :readonly="readonly"
            :placeholder="placeholder"
            v-model="inputValue"
        />

        <p class="text-danger" v-if="error" class="error">{{ error }}</p>
    </div>
    </div>
  `,
};
