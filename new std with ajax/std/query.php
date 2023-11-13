$query = "SELECT s.studentid, s.studentname, s.gender, 
GROUP_CONCAT(DISTINCT ss.subjectName) AS subjectNames, 
GROUP_CONCAT(DISTINCT g.id) AS game_name, 
GROUP_CONCAT(DISTINCT ss.marks) AS marks, 
GROUP_CONCAT(DISTINCT sc.city) AS cities, 
GROUP_CONCAT(DISTINCT sc.province) AS provinces, 
s.image 
FROM student s 
LEFT JOIN games g ON s.fkgameid = g.id 
LEFT JOIN studentsubject ss ON s.studentid = ss.fkstudentid 
LEFT JOIN studentcity sc ON s.studentid = sc.fkstudentid 
WHERE s.studentid = '$studentID'
GROUP BY s.studentid, s.studentname, s.gender, g.name, s.image";
    $result = mysqli_query($connection, $query);
    $row = mysqli_fetch_array($result);
    print_r($row);




    SELECT
  s.studentid,
  s.studentname,
  s.gender,
  GROUP_CONCAT(DISTINCT ss.subjectName) AS subjectNames,
  (
    SELECT GROUP_CONCAT(DISTINCT name)
    FROM games
    WHERE FIND_IN_SET(games.id, s.fkgameid)
  ) AS game_names,
  GROUP_CONCAT(DISTINCT ss.marks) AS marks,
  GROUP_CONCAT(DISTINCT sc.city) AS cities,
  GROUP_CONCAT(DISTINCT sc.province) AS provinces,
  s.image
FROM student s
LEFT JOIN studentsubject ss ON s.studentid = ss.fkstudentid
LEFT JOIN studentcity sc ON s.studentid = sc.fkstudentid
WHERE s.studentid = '1'
GROUP BY s.studentid, s.studentname, s.gender, s.image, s.fkgameid;
