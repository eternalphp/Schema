<?php 

use framework\Database\Schema\Control;
use wordControl\wordControl;
use wordControl\wordStyle;

class phpWord{
	
	private $filename;
	private $title;
	
	public function __construct($filename,$title){
		$this->filename = $filename;
		$this->title = $title;
	}
	
	public function create($tables){
		if($tables){
			
			//生成文档
			$wordControl = new wordControl();
						
			//创建段落
			$wordControl->createSection(function($wordSection){
				$wordSection->align("center");
				$wordSection->createText(function($wordText){
					$wordText->text($this->title)->size($wordText->getFontSize("二号"))->font("微软雅黑")->spacing(0)->bold();
				});
			});

			$wordControl->createSection(function($wordSection){
				$wordSection->align("center");
				$wordSection->createText(function($wordText){
					$wordText->text("V1.0")->size($wordText->getFontSize("五号"))->font('微软雅黑')->color('#333333')->bold();
				});
			});

			$wordControl->createSection(function($wordSection){
				$wordSection->align("center");
				$wordSection->createText(function($wordText){
					$wordText->text("2019-08-07")->font("Tahoma")->size(11)->bold();
				});
			});

			$wordControl->createSection(function($wordSection){
				$wordSection->align("center")->spacing(10);
				$wordSection->createText(function($wordText){
					$wordText->text("");
				});
			});
			
			$index = 1;
			foreach($tables as $k=>$table){
				
				$title = sprintf("%d、%s ",$index,$table->getComment());
				
				$wordControl->createSection(function($wordSection) use ($title,$table){
					$wordSection->pStyle();
					$wordSection->createText(function($wordText) use ($title){
						$wordText->text($title)->font("微软雅黑")->size($wordText->getFontSize("二号"))->bold();
					});
					$wordSection->createText(function($wordText) use ($table){
						$wordText->text(sprintf("(%s)",$table->getName()))->font("微软雅黑")->size($wordText->getFontSize("五号"))->bold();
					});
				});
				
				//创建表格
				$wordControl->createTable(function($wordTable) use ($table){
					
					//设置表格边框样式
					$wordTable->borders(function($wordTableBorders){
						$wordTableBorders->get('left')->size('10');
						$wordTableBorders->get('right')->size('10');
						$wordTableBorders->get('top')->size('10');
						$wordTableBorders->get('bottom')->size('10');
					});
					
					//创建表格行
					$wordTable->createRow(function($wordTableRow){
						
						//创建表格列
						$wordTableRow->createCell(function($wordTableCell){
							$wordTableCell->width(1000);
							$wordTableCell->createSection(function($wordSection){
								$wordSection->createText(function($wordText){
									$wordText->text("字段")->bold();
								});
							});
						});

						$wordTableRow->createCell(function($wordTableCell){
							$wordTableCell->width(1000);
							$wordTableCell->createSection(function($wordSection){
								$wordSection->createText(function($wordText){
									$wordText->text("类型")->bold();
								});
							});
							
						});

						$wordTableRow->createCell(function($wordTableCell){
							$wordTableCell->width(1000);
							$wordTableCell->createSection(function($wordSection){
								$wordSection->createText(function($wordText){
									$wordText->text("说明")->bold();
								});
							});
						});
					
					});
					
					$fields = $table->getFields();
					if($fields){
						foreach($fields as $field){
							
							$this->field = $field;
							
							//创建表格行并为此行创建模板
							$wordTable->createRow(function($wordTableRow){
								
								$wordTableRow->createCell(function($wordTableCell){
									$wordTableCell->width(1000);
									$wordTableCell->createSection(function($wordSection){
										$wordSection->createText(function($wordText){
											if($this->field->getAlias() != null){
												$wordText->text($this->field->getAlias())->color("#ff0000");
											}else{
												$wordText->text($this->field->getName())->color("#ff0000");
											}
										});
									});
								});

								$wordTableRow->createCell(function($wordTableCell){
									$wordTableCell->width(1000);
									$wordTableCell->createSection(function($wordSection){
										$wordSection->createText(function($wordText){
											$wordText->text($this->field->getType());
										});
									});
									
								});

								$wordTableRow->createCell(function($wordTableCell){
									$wordTableCell->width(1000);
									$wordTableCell->createSection(function($wordSection){
										$wordSection->createText(function($wordText){
											$wordText->text($this->field->getComment());
										});
									});
								});
							
							});
							
						}
					}
					
				});
				
				
				$wordControl->createSection(function($wordSection){
					$wordSection->align("center")->spacing(3);
					$wordSection->createText(function($wordText){
						$wordText->text("");
					});
				});
				
				$index++;
				
			}
		}

		$wordControl->createXML()->save($this->filename);
	}
}

?>