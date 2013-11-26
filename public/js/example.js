/**
 * Created with JetBrains PhpStorm.
 * User: logo
 * Date: 11/16/13
 * Time: 10:57 AM
 * To change this template use File | Settings | File Templates.
 */

var an_object = { 'foo' : 'bar', a : function() {console.log(this); } };

var new_obj = Object.create(an_object);

function Class(options) {


}

Class.prototype = an_object;


var bar = new Class({});




// altceva incepe aici
function Model(uri) {
    this.uri = uri;
}

Model.prototype.base_url = 'google.com';


function User(id) {
    Model.apply(this, '/users/' + id); //a
}

User.prototype = new Model(); //b
//-----------

var c = new User();

//---------------------


var a = { 'name' : 'A', 'getName' : function() { console.log(this.name); } };

var b = { 'name' : 'B' };

a.getName(); /// A

b.getName(); // function noneexistent

a.getName.call(b); // B
a.getName.apply(b); // B
var new_func = a.getName.bind(b); /// new_func() => b

//---------------------------------------
//SCOPE

var App = function() {
    var config = {};
    var obj = {};
    obj.init = function() {

    };

    this.treaba = 'ceva';
    return obj;
}();
