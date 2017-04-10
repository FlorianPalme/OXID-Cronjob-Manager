/**
 * @author Florian Palme <info@florian-palme.de>
 */
module.exports = {
    build: {
        files: [
            {
                expand: true,
                cwd: 'src/',
                src: '**',
                dest: 'copy_this/modules/fp/oxid-cronjob-manager/',
                dot: true
            }
        ]
    }
};