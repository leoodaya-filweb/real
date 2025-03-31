const { toRefs, ref } = Vue;

export default {
	props: ['spaceMessage', 'addClass'],
	setup(props, { emit }) {
		const { spaceMessage, addClass } = toRefs(props);
        
		const viewFile = (file) => {
			window.open(file.viewerUrl);
		}

        const setClass = (file) => {
            let generatedClass = [addClass.value];

            if (file.isImage) {

                if (spaceMessage.value.isSender) {
                    generatedClass.push('justify-content-lg-end');
                    generatedClass.push('pr-0');
                }
                else {
                    generatedClass.push('pl-0');
                }
            }
            else {
                generatedClass.push('bg-light-primary text-dark-50 p-2');
            }

            return generatedClass.join(' ');
        }

		return {
			spaceMessage,
			viewFile,
            setClass
		}
	},
  	template: `
		<div v-if="spaceMessage.files.length">
            <div v-for="file in spaceMessage.files" :key="file.id" class="message-attachments d-flex align-items-center b-2 mb-1 justify-content-between" :class="setClass(file)">
                <div v-if="file.isImage">
                    <div @click="viewFile(file)" :title="file.name">
                        <img :src="file.imagePath" class="img-fluid br4px mw-300 img-thumbnail">
                    </div>
                </div>
                <div class="d-flex align-items-center" v-if="!file.isImage">
                    <div @click="viewFile(file)" :title="file.name">
                        <img :src="file.displayPath" class="img-fluid br4px img-thumbnail">
                    </div>
                    <div class="mx-3" :title="file.name">
                        <div class="font-weight-bold">
                            <a class="text-dark-65 text-hover-primary" :href="file.viewerUrl" target="_blank" v-text="file.truncatedName"></a>
                        </div>
                        <div v-html="file.fileSize"></div>
                        <div v-text="file.extension"></div>
                    </div>
                </div>
                <a  v-if="!file.isImage" title="Download" download :href="file.downloadUrl" class="btn btn-icon btn-outline-secondary">
                    <i class="fa fa-download"></i>
                </a>
            </div>
        </div>
	`
}