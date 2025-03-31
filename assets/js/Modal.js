export default {
	emits: ['close'],
	setup(props, { emit }) {
		const close = () => {
			emit('close');
		}

		return {
			close
		}
	},
  	template: `
		<transition name="modal">
			<div class="modal-mask">
				<div class="modal-wrapper">
					<div class="modal-container">
						<div class="modal-header">
							<slot name="header">
								default header
							</slot>
						</div>

						<div class="modal-body">
							<slot name="body">
								default body
							</slot>
						</div>

						<div class="modal-footer">
							<slot name="footer">
								default footer
								<button class="modal-default-button" @click="$emit('close')">
									OK
								</button>
							</slot>
						</div>
					</div>
				</div>
			</div>
		</transition>
	`
}


// <span>
// 	<a href="#" @click.prevent="close">
// 		<i class="far fa-window-close display5-xl"></i>
// 	</a>
// </span>