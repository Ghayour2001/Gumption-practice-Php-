<form action="" method="post" enctype="multipart/form-data">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="editName">Student Name</label>
                                <input id="editName" class="form-control" type="text" name="name" value="<?php echo isset($row1['studentname']) ? $row1['studentname'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="email">Email</label>
                                <input id="email" class="form-control" type="email" name="email" value="<?php echo isset($row1['studentemail']) ? $row1['studentemail'] : ''; ?>">
                            </div>
                            <div class="form-group">
                                <label for="dob">Date of Birth </label>
                                <input id="dob" class="form-control" type="date" name="dob">
                            </div>
                            <div class="form-group">
                                <label for="country">Country</label>
                                <select name="country" id="country" class="form-control">
                                    <option value="">--Select Country--</option>
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM countries');
                                    $countries = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($countries as $country) {
                                        $id = $country['id'];
                                        $countryName = $country['name'];
                                    ?>
                                        <option value="<?php echo $id; ?>"><?php echo $countryName; ?></option>
                                    <?php
                                    }
                                    if (empty($countries)) {
                                    ?>
                                        <option value="0">Country not found</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="province">Province</label>
                                <select name="province" id="province" class="form-control"></select>
                            </div>

                            <div class="form-group">
                                <label for="city">City</label>
                                <select name="city" id="city" class="form-control">

                                </select>
                            </div>

                            <div class="form-group">
                                <label for="subject">Subject</label>
                                <select name="subject[]" id="subject" class="form-control" multiple="multiple">
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM subject');
                                    $subjects = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($subjects as $subject) {
                                        $id = $subject['subjectid'];
                                        $subjectName = $subject['subjectname'];
                                    ?>
                                        <option value="<?php echo $id; ?>"><?php echo $subjectName; ?></option>
                                    <?php
                                    }
                                    if (empty($subjects)) {
                                    ?>
                                        <option value="0">subjects not found</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group text-center">
                                <div class="image-div">
                                    <img src="images/avatar.png" alt="" id="selected-img">
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="address">Image</label>
                                <input type="file" name="image" class="form-control" id="image">
                            </div>

                            <div class="form-group">
                                <label for="address">Address</label>
                                <textarea name="address" id="address" class="form-control" cols="30" rows="3"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <input type="radio" name="gender" id="gender" value="Male">Male
                                <input type="radio" name="gender" id="gender" value="Female">Female
                            </div>
                            <div class="form-group">
                                <label for="subject">Skills</label>
                                <select name="skill[]" id="skill" class="form-control" multiple="multiple">
                                    <?php
                                    $select = mysqli_query($connection, 'SELECT * FROM Skill');
                                    $Skills = mysqli_fetch_all($select, MYSQLI_ASSOC);
                                    foreach ($Skills as $Skill) {
                                        $id = $Skill['skillid'];
                                        $skillName = $Skill['skillname'];
                                    ?>
                                        <option value="<?php echo $id; ?>"><?php echo $skillName; ?></option>
                                    <?php
                                    }
                                    if (empty($Skills)) {
                                    ?>
                                        <option value="0">Skills not found</option>
                                    <?php
                                    }
                                    ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <?php if (isset($stdID)) { ?>
                                    <button type="submit" name="update" class="btn btn-warning">Update</button>
                                <?php } else { ?>
                                    <button type="submit" name="save" class="btn btn-primary">Save</button>
                                <?php } ?>
                            </div>
                        </div>
                    </div>

                </form>
                </form>
                </form>