node('docker') {
    ansiColor('xterm') {

        stage('Checkout') {
            checkout scm
        }

        stage('Pulling') {
            sh 'docker pull jakzal/phpqa'
        }

        stage('Prepare directory') {
            sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa chmod -R 777 build'
            sh 'rm -rf build/logs'
            sh 'mkdir -p build/logs'
        }

        stage('Install dependencies') {
            sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa composer install --ignore-platform-reqs --no-scripts --no-progress --no-suggest'
        }

        stage('Run unit tests') {
            sh 'cp phpunit.xml.dist phpunit.xml'
            sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa vendor/bin/simple-phpunit'
        }

        stage("Testing") {
            parallel (
                "PHPCodeSniffer": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpcs --warning-severity=0 --standard=PSR2 --encoding=UTF-8 --ignore="*.js" src/'
                },

                "PHPStan": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpstan analyse src/ || exit 0'
                },

                "PhpMetrics": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpmetrics --report-html=build/logs/phpmetrics.html src/ || exit 0'
                    publishHTMLReport('build/logs', 'phpmetrics.html', 'PHPMetrics')
                },

                "PHPMessDetector": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpmd src/ xml cleancode,codesize,unusedcode --reportfile build/logs/pmd.xml || exit 0'
                    replaceFilePath('build/logs/pmd.xml')
                    pmd canRunOnFailed: true, pattern: 'build/logs/pmd.xml'
                },

                "PHPMagicNumberDetector": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpmnd src/ --exclude=tests --progress --non-zero-exit-on-violation --ignore-strings=return,condition,switch_case,default_parameter,operation || exit 0'
                },

                "PHPCopyPasteDetector": {
                    sh 'docker run --rm -v $(pwd):/project -w /project jakzal/phpqa phpcpd --log-pmd build/logs/pmd-cpd.xml src/ || exit 0'
                    replaceFilePath('build/logs/pmd-cpd.xml')
                    dry canRunOnFailed: true, pattern: 'build/logs/pmd-cpd.xml'
                }
            )
        }
    }
}

def replaceFilePath(filePath) {
    sh "sed -i 's#/project/#${workspace}/#g' ${filePath}"
}

def publishHTMLReport(reportDir, file, reportName) {
    if (fileExists("${reportDir}/${file}")) {
        publishHTML(target: [
            allowMissing         : true,
            alwaysLinkToLastBuild: true,
            keepAll              : true,
            reportDir            : reportDir,
            reportFiles          : file,
            reportName           : reportName
        ])
    }
}
