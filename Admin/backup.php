<div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <p class="display-4">Categories Management</p>
                    <a href="Category_create.php" class="btn btn-success mt-2">Create New Category</a>
                </div>
                <div class="card-body">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <td style="width: 10px">#</td>
                                <td>Name</td>
                                <td>Description</td>
                                <td>Action</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                              if($result){
                                  $i = 1;
                                  foreach($result as $people){
                            ?>
                              <tr>
                                  <td><?php echo $i ?></td>
                                  <td><?php echo escape($people['name']); ?></td>
                                  <td><?php echo escape($people['description']); ?></td>
                                  <td>
                                      <a href="edit_cate.php?id=<?php echo $people['id'];?>" class="btn btn-info btn-sm">Edit</a>
                                      <a href="delete_cate.php?id=<?php echo $people['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Do you want to delete <?php echo $people['name']; ?>');">Delete</a>
                                  </td>
                              </tr>
                            <?php
                              $i++;
                                }
                              }
                            ?>
                                
                        </tbody>
                    </table>
                    <ul class="pagination justify-content-end mt-3">
                      <li class="page-item"> <a href="?pageno=1" class="page-link">First</a></li>
                      <li class="page-item <?php if($pageno <= 1){echo "disabled";} ?>"> 
                        <a href="<?php if($pageno <= 1){echo "disabled";}else{ echo "?pageno=".($pageno-1);}?>" class="page-link">
                          Previous
                        </a>
                      </li>
                      <li class="page-item"> <a href="" class="page-link"><?php echo escape($pageno); ?></a></li>
                      <li class="page-item <?php if($pageno >= $totalpage){echo "disabled";} ?>">
                        <a 
                          href="<?php if($pageno >= $totalpage){echo "disabled";}else{echo "?pageno=".($pageno+1);}?>" class="page-link">
                        Next</a>
                      </li>
                      <li class="page-item"><a href="?pageno=<?php echo $totalpage; ?>" class="page-link">End</a></li>
                    </ul>
                </div>
            </div>
          </div>