ALTER TABLE  `account` CHANGE  `type`  `type` ENUM(  'income',  'expense',  'buffer',  'saving',  'balance' ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;
INSERT INTO  `yourcashflow`.`account` (
  `id` ,
  `name` ,
  `type` ,
  `currency`
)
  VALUES (
    NULL ,  'balance',  'balance',  'RON'
  );