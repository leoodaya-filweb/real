class InputListWidget {

    constructor({widgetId, name, label, type, inputType}) {
        this.widgetId = widgetId;
        this.name = name;
        this.label = label;
        this.type = type;
        this.inputType = inputType;
    }

    init() {
        let self = this;
        const input =  $(`#${self.widgetId} ${self.type}[name="input"]`),
            addBtn = $(`#${self.widgetId} .btn-add`),
            listContainer = $(`#${self.widgetId} .list-container`);

        const generateHtml = () => {
            let data = input.val();
            const result = data ? data.trim(): '';

            if(result) {
                let inp = '';
                if (self.type == 'input') {
                    inp = `<input placeholder="Enter ${self.label}" type="${self.inputType}" class="form-control" name="${self.name}" value="${result}" required>`;
                }
                else if(self.type == 'textarea') {
                    inp = `<textarea placeholder="Enter ${self.label}" type="text" class="form-control" name="${self.name}" required>${result}</textarea>`;
                }

                listContainer.append(`
                    <div class="input-group mb-2">
                        <div class="input-group-prepend">
                            <button class="btn btn-secondary handle-sortable" type="button">
                                <i class="fas fa-arrows-alt"></i>
                            </button>
                        </div>
                        ${inp}
                        <div class="input-group-append">
                            <button class="btn btn-danger btn-remove" type="button">
                                <i class="fa fa-trash"></i>
                            </button>
                        </div>
                    </div>
                `);
                input.val('').focus();
            }
        };

        input.on('keydown', (e) => {
            if(e.key == 'Enter') {
                if (self.type == 'input') {
                    e.preventDefault();
                    generateHtml();
                }
            }
        });

        addBtn.on('click', () => {
            generateHtml();
        });

        $(document).on('click', `#${self.widgetId} .btn-remove`, function() {
            $(this).closest('.input-group').remove();
        });
    }
}

