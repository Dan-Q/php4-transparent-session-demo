FROM nouphet/docker-php4
RUN echo 'register_globals = "On"\nsession.use_cookies = "Off"\nsession.use_trans_sid = "On"\n' > /etc/php4/conf.d/999_php4-exp.ini
