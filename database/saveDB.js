const fs = require("fs");
const cp = require("child_process");
const process = require("process");


function saveDB(db_name, file_name) {
    let files_in_dir = fs.readdirSync(".");

    files_in_dir = files_in_dir.filter(f => {
        return f.includes(db_name);
    });


    // // Preparing the Command 
    const CMD_COMMAND = `mysqldump --user=root --password="" ${db_name} > ${file_name || db_name}${files_in_dir.length || ""}.sql`;

    cp.exec(CMD_COMMAND, function (e,f) { 
        console.log(f); 
    });

}


if( db_name = process.argv[2] ) {
    saveDB( db_name );
} else {
    
}
