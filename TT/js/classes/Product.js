function mProduct (terms)
{
	this.terms = new Array();
	
	if(terms instanceof Array)
	{
		this.terms = terms;
	}
	if(terms instanceof MathObject)
	{
		this.terms.push(terms);
	}
	
}
$.extend(mProduct.prototype, MathObject.prototype);

mProduct.prototype.clone = function()
{
	return new mProduct(this.terms);
}

mProduct.prototype.valueOf = function()
{
	var product = 1;
	for(var i=0; i<this.terms.length; i++)
	{
		product *= this.terms[i].valueOf();
	}
	return product;
}

mProduct.prototype.exists = function()
{
	for(var i=0; i<this.terms.length; i++)
	{
		if(!this.terms[i].exists()) return false;
	}
	return true;
}

mProduct.prototype.opposite = function()
{
	if(this.terms[0] instanceof mNumber)
	{
		if(this.terms[0].isMinusOne()) this.terms.splice(0,1);
		else this.terms[0].opposite();
	}
}

mProduct.prototype.simplify = function()
{
	return this;
}

mProduct.prototype.gcd = function(f)
{
	var gcd = this.terms[0];
	for(var i=1; i<this.terms.length; i++)
	{
		gcd = gcd.gcd(this.terms[i]);
	}
	return gcd.gcd(f);
}

mProduct.prototype.mcm = function(f)
{
	var mcm = this.terms[0];
	for(var i=1; i<this.terms.length; i++)
	{
		mcm = mcm.mcm(this.terms[i]);
	}
	return mcm.mcm(f);
}

mProduct.prototype.plus = function(f)
{
	return new mSum([this,f]);
}

mProduct.prototype.dot = function(f)
{
	this.terms.push(f);
	return this;
}

mProduct.prototype.pow = function(f)
{
	return new Power(this,f);
}

mProduct.prototype.toTex = function()
{
	var t = this.terms[0].dotTex(true);
	for(var i=1; i<this.terms.length; i++)
	{
		t += this.terms[i].dotTex();
	}
	return t;
}