// RSA, a suite of routines for performing RSA public-key computations in
// JavaScript.
//
// Requires BigInt.js and Barrett.js.
//
// Copyright 1998-2005 David Shapiro.
//
// You may use, re-use, abuse, copy, and modify this code to your liking, but
// please keep this header.
//
// Thanks!
// 
// Dave Shapiro
// dave@ohdave.com 

function RSAKeyPair(encryptionExponent, decryptionExponent, modulus)
{
	this.e = biFromHex(encryptionExponent);
	this.d = biFromHex(decryptionExponent);
	this.m = biFromHex(modulus);
	// We can do two bytes per digit, so
	// chunkSize = 2 * (number of digits in modulus - 1).
	// Since biHighIndex returns the high index, not the number of digits, 1 has
	// already been subtracted.
	this.chunkSize = 2 * (biHighIndex(this.m));
	this.radix = 16;
	this.barrett = new BarrettMu(this.m);

	this.length = (biHighIndex(this.m)+1) * this.radix - 1 ;
}

function twoDigit(n)
{
	return (n < 10 ? "0" : "") + String(n);
}

function encryptedString(key, s)
	// Altered by Rob Saunders (rob@robsaunders.net). New routine pads the
	// string after it has been converted to an array. This fixes an
	// incompatibility with Flash MX's ActionScript.
{
	var a = new Array();
	var sl = s.length;
	var i = 0;
	while (i < sl) {
		a[i] = s.charCodeAt(i);
		i++;
	}
	
	while (a.length % key.chunkSize != 0) {
		a[i++] = 0;
	}

	var al = a.length;
	var result = "";
	var j, k, block;
	for (i = 0; i < al; i += key.chunkSize) {
		block = new BigInt();
		j = 0;
		for (k = i; k < i + key.chunkSize; ++j) {
			block.digits[j] = a[k++];
			block.digits[j] += a[k++] << 8;
		}
		var crypt = key.barrett.powMod(block, key.e);
		var text = key.radix == 16 ? biToHex(crypt) : biToString(crypt, key.radix);
		result += text + " ";
	}
	return result.substring(0, result.length - 1); // Remove last space.
}

function decryptedString(key, s)
{
	var blocks = s.split(" ");
	var result = "";
	var i, j, block;
	for (i = 0; i < blocks.length; ++i) {
		var bi;
		if (key.radix == 16) {
			bi = biFromHex(blocks[i]);
		}
		else {
			bi = biFromString(blocks[i], key.radix);
		}
		block = key.barrett.powMod(bi, key.d);
		for (j = 0; j <= biHighIndex(block); ++j) {
			result += String.fromCharCode(block.digits[j] & 255,
			                              block.digits[j] >> 8);
		}
	}
	// Remove trailing null, if any.
	if (result.charCodeAt(result.length - 1) == 0) {
		result = result.substring(0, result.length - 1);
	}
	return result;
}

function pad(str, c, n)
{
	while(str.length<n)
	{
		str = str + c;
	}
	return str;
}

function subInt(num, start, length)
{
	var start_byte = Math.floor(start / 8) ;
	var start_bit = start % 8;
	var byte_length = Math.floor(length / 8) ;
	var bit_length = length % 8 ;
	if(bit_length)
	{
		byte_length++;
	}
	num = biDivide(num, biFromNumber( 1 << start_bit ) );
	var tmp = biToBin(num);
	tmp = tmp.substring(start_byte, start_byte+byte_length);
	return biFromBin(tmp);
}

function encryptedString2(key, s)
{
	var chunkLen = key.length;
	var blockLen = Math.ceil(chunkLen/8) ;
	var plainData;
	s += '\1';
	
	plainData = biFromBin(s);
	var dataLen = biNumBits(plainData);
	
	var curr_pos = 0;
	var enc_data = '';
	
	while( curr_pos < dataLen )
	{
		var tmp = subInt(plainData, curr_pos, chunkLen);
		var crypt = key.barrett.powMod(tmp, key.e);
		enc_data = biToHex(crypt) +enc_data;
		curr_pos += chunkLen;
	}
	return enc_data;
}

