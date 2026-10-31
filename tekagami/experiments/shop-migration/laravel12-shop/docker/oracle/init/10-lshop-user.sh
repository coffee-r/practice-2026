#!/bin/bash
# lshop Oracle ユーザーを SYS/SYSDBA 接続で作成する。
# このスクリプトは gvenzl/oracle-free の init ディレクトリで実行される。
set -e

sqlplus -S "sys/${ORACLE_PASSWORD}@//localhost:1521/FREEPDB1 as sysdba" << 'SYSDBA'
WHENEVER SQLERROR CONTINUE
DROP USER lshop CASCADE;
WHENEVER SQLERROR EXIT SQL.SQLCODE
CREATE USER lshop IDENTIFIED BY lshop;
GRANT CONNECT, RESOURCE TO lshop;
ALTER USER lshop QUOTA UNLIMITED ON USERS;
EXIT;
SYSDBA
