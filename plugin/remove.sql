DELETE FROM events where (handler LIKE '%ArMenuHandler@%' AND ((data is NULL) or (handler not like '%sendmail%'))) OR (handler LIKE '%ArTodoHandler@%' AND ((data is NULL) or (handler not like '%sendmail%'))) LIMIT 100