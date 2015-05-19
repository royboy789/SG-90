module.exports = function(grunt) {
	grunt.initConfig({
		less: {
			development: {
				options: {
					compress: true,
					yuicompress: true,
					optimization: 2
				},
				files: {
					"includes/css/sgStyles.css": "includes/css/sgStyles.less",
				}
			}
		},
		watch: {
			styles: {
				files: ['includes/**/*.less'],
				tasks: ['less'],
				options: {
					nospawn: true
				}
			}
		}
	});
	grunt.loadNpmTasks('grunt-contrib-less');
	//grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.registerTask('default', ['less']);
};