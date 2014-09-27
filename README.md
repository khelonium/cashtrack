cashtrack
=========

Install
-------

Install Vagrant and then do a vagrant up. If you encounter problems, check the travis build file for some tips





What this does
--------------
Nothing for you , but used to track my expenses


About
-----

This personal accounting software uses accounts to track expenses. Expenses are monitored in a monthly basis, but it can
also show custom intervals.

Expenses are related to accounts. Accounts can be income, expense, buffer (money accounts), saving and balance. Balance
is a special case to make it easier to compute the amount of money available in each month.

Any kind of operations is tracked as a  transaction. A transaction has a from account and a to account.  Knowing the
from an to values, the software can track all operations for all accounts.

When accounts receive money, it is called debit. When an account has expenses, it is called credit.

The debit - credit is called the account balance.  This can be computed only over a period of time. This value is called
AccountValue.

For a whole month, the Balance is computed by having in consideration all AccountValues.  For the month Balance,
the buffers and savings are not considered.


