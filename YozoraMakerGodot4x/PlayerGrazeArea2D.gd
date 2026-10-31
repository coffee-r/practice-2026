extends Area2D

# かすり中の星の数
var grazing_count = 0

# かすり判定に星が入った時
func _on_body_entered(body):
	grazing_count += 1
	print('graze in ' + var_to_str(grazing_count))

# かすり判定から星が出て行った時
func _on_body_exited(body):
	grazing_count -= 1
	print('graze out ' + var_to_str(grazing_count))
