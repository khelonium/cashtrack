#!/bin/bash
php phpmetrics.phar  --excluded-dirs=config,view,tests,Double --report-html=public/metrics.html module/
