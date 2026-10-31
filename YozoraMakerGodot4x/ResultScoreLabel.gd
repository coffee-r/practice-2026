extends Label

func _ready():
	# textにスコアを反映
	text = var_to_str(Score.value)
